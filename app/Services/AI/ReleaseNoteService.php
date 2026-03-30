<?php

namespace App\Services\AI;

use App\Models\BrandVoice;
use App\Models\Draft;
use App\Models\Post;
use App\Models\SourceItem;
use App\Services\LLM\LLMFactory;
use App\Services\SettingsService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ReleaseNoteService
{
    public function __construct(
        private LLMFactory $llmFactory,
        private SettingsService $settings,
    ) {}

    public function detectLastReleaseDate(?int $repoId = null): ?Carbon
    {
        // Check published release note posts first
        $lastReleasePost = Post::query()
            ->where('is_release_note', true)
            ->where('is_published', true)
            ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
            ->orderByDesc('published_at')
            ->first();

        if ($lastReleasePost) {
            return $lastReleasePost->published_at;
        }

        // Check source items of type "release"
        $lastRelease = SourceItem::query()
            ->where('type', 'release')
            ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
            ->orderByDesc('created_on_github_at')
            ->first();

        return $lastRelease?->created_on_github_at;
    }

    public function suggestNextVersion(?int $repoId = null): string
    {
        // Find the latest version tag from source items or posts
        $latestVersion = Post::query()
            ->where('is_release_note', true)
            ->whereNotNull('version')
            ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
            ->orderByDesc('published_at')
            ->value('version');

        if (! $latestVersion) {
            $latestVersion = SourceItem::query()
                ->where('type', 'release')
                ->whereNotNull('title')
                ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
                ->orderByDesc('created_on_github_at')
                ->value('title');
        }

        if ($latestVersion && preg_match('/(\d+)\.(\d+)\.(\d+)/', $latestVersion, $matches)) {
            return $matches[1].'.'.$matches[2].'.'.($matches[3] + 1);
        }

        return '1.0.0';
    }

    public function gatherSourceItems(Carbon $since, ?Carbon $until = null, ?int $repoId = null): Collection
    {
        return SourceItem::query()
            ->where('is_user_facing', true)
            ->where('created_on_github_at', '>', $since)
            ->when($until, fn ($q) => $q->where('created_on_github_at', '<=', $until))
            ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
            ->orderByDesc('created_on_github_at')
            ->limit(50)
            ->get();
    }

    public function countAvailableItems(Carbon $since, ?Carbon $until = null, ?int $repoId = null): int
    {
        return SourceItem::query()
            ->where('is_user_facing', true)
            ->where('created_on_github_at', '>', $since)
            ->when($until, fn ($q) => $q->where('created_on_github_at', '<=', $until))
            ->when($repoId, fn ($q) => $q->where('repository_id', $repoId))
            ->count();
    }

    public function groupByCategory(Collection $items): array
    {
        $groups = [
            'new' => [],
            'improved' => [],
            'fixed' => [],
            'performance' => [],
            'security' => [],
        ];

        foreach ($items as $item) {
            $category = $this->detectCategory($item);
            $groups[$category][] = $item;
        }

        return array_filter($groups);
    }

    public function generate(Collection $items, BrandVoice $voice, string $version, ?int $repoId = null): Draft
    {
        $llm = $this->llmFactory->make();
        $language = $voice->language ?? $this->settings->get('general', 'language', 'de');

        $grouped = $this->groupByCategory($items);
        $itemsContext = $this->formatGroupedItems($grouped);

        $languageInstruction = PromptHelper::getLanguageInstruction($language);
        $voiceInstruction = PromptHelper::getVoiceInstruction($voice);
        $noGoWords = PromptHelper::getNoGoWordsInstruction($voice);

        $systemPrompt = <<<PROMPT
You are a product release note writer. Your job is to create comprehensive, structured release notes that summarize all changes in a software release.

{$languageInstruction}
{$voiceInstruction}
{$noGoWords}

Rules:
- Write for end users, not developers
- Group changes by category using ## headings: New, Improved, Fixed, Performance, Security
- Only include sections that have items
- Each item should be a bullet point with a brief, benefit-focused description
- Focus on the benefit, not the technical implementation
- Never invent features not supported by the source material
- Keep each bullet point to 1-2 sentences maximum
- Do NOT include any secrets, tokens, API keys, or credentials
- Do NOT use generic AI phrases like "we're excited to announce"

Respond with a JSON object:
- "title": release note title including version number (max 80 characters)
- "teaser": one-sentence summary of the release (max 160 characters)
- "body": markdown-formatted release notes with ## section headings and bullet points
- "category": always "new" for release notes
- "confidence": 0.0 to 1.0 (how confident you are this is accurate)

Return ONLY valid JSON, no markdown formatting.
PROMPT;

        $response = $llm->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "Generate release notes for version {$version} from these changes:\n\n{$itemsContext}"],
        ], ['temperature' => 0.4, 'max_tokens' => 4096]);

        $result = json_decode(PromptHelper::cleanJsonResponse($response), true);

        if (! is_array($result) || empty($result['title']) || empty($result['body'])) {
            throw new \RuntimeException('LLM did not return valid release notes.');
        }

        $draft = Draft::create([
            'repository_id' => $repoId,
            'title' => $result['title'],
            'teaser' => $result['teaser'] ?? null,
            'body_markdown' => $result['body'],
            'body_html' => Str::markdown($result['body']),
            'category' => PromptHelper::mapCategory($result['category'] ?? 'new'),
            'status' => 'draft',
            'confidence_score' => $result['confidence'] ?? 0.7,
            'source_bundle' => $items->map(fn ($i) => [
                'id' => $i->id,
                'type' => $i->type,
                'title' => $i->title,
            ])->toArray(),
            'brand_voice_id' => $voice->id,
            'is_release_note' => true,
            'version' => $version,
        ]);

        $draft->sourceItems()->attach($items->pluck('id'));

        return $draft;
    }

    private function detectCategory(SourceItem $item): string
    {
        $metadata = $item->metadata ?? [];
        $labels = array_map('strtolower', $metadata['labels'] ?? []);
        $title = strtolower($item->title ?? '');

        // Check labels first
        foreach ($labels as $label) {
            if (in_array($label, ['bug', 'fix', 'bugfix', 'hotfix'])) {
                return 'fixed';
            }
            if (in_array($label, ['feature', 'new', 'enhancement'])) {
                return 'new';
            }
            if (in_array($label, ['performance', 'speed', 'optimization'])) {
                return 'performance';
            }
            if (in_array($label, ['security', 'vulnerability', 'cve'])) {
                return 'security';
            }
        }

        // Check title keywords
        if (preg_match('/\b(fix|bug|patch|resolve|hotfix)\b/', $title)) {
            return 'fixed';
        }
        if (preg_match('/\b(add|new|feature|introduce|implement)\b/', $title)) {
            return 'new';
        }
        if (preg_match('/\b(perf|speed|fast|optim|cache)\b/', $title)) {
            return 'performance';
        }
        if (preg_match('/\b(secur|auth|vuln|cve|xss|csrf|inject)\b/', $title)) {
            return 'security';
        }

        return 'improved';
    }

    private function formatGroupedItems(array $grouped): string
    {
        $sections = [];
        $categoryLabels = [
            'new' => 'New Features',
            'improved' => 'Improvements',
            'fixed' => 'Bug Fixes',
            'performance' => 'Performance',
            'security' => 'Security',
        ];

        foreach ($grouped as $category => $items) {
            $label = $categoryLabels[$category] ?? ucfirst($category);
            $section = "## {$label}\n\n";
            foreach ($items as $item) {
                $section .= PromptHelper::formatSourceItemContext($item)."\n\n---\n\n";
            }
            $sections[] = $section;
        }

        return implode("\n", $sections);
    }
}
