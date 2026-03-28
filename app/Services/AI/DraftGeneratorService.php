<?php

namespace App\Services\AI;

use App\Models\BrandVoice;
use App\Models\Draft;
use App\Models\SourceItem;
use App\Services\LLM\LLMFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DraftGeneratorService
{
    public function __construct(private LLMFactory $llmFactory) {}

    public function generateDrafts(Collection $sourceItems, BrandVoice $voice): Collection
    {
        $drafts = collect();
        $llm = $this->llmFactory->make();

        // Group related items
        $groups = $this->groupRelatedItems($sourceItems);

        foreach ($groups as $group) {
            try {
                $draft = $this->generateDraftForGroup($group, $voice, $llm);
                if ($draft) {
                    $drafts->push($draft);
                }
            } catch (\Exception $e) {
                \Log::warning('Draft generation failed for group: '.$e->getMessage());
            }
        }

        return $drafts;
    }

    public function regenerateDraft(Draft $draft, BrandVoice $voice): Draft
    {
        $llm = $this->llmFactory->make();
        $sourceItems = $draft->sourceItems;

        if ($sourceItems->isEmpty()) {
            throw new \RuntimeException('No source items linked to this draft.');
        }

        $result = $this->callLLM($sourceItems, $voice, $llm);
        if (! $result) {
            throw new \RuntimeException('LLM did not return a valid draft.');
        }

        $draft->update([
            'title' => $result['title'],
            'teaser' => $result['teaser'] ?? null,
            'body_markdown' => $result['body'],
            'body_html' => Str::markdown($result['body']),
            'category' => $result['category'] ?? 'improved',
            'confidence_score' => $result['confidence'] ?? 0.7,
            'status' => 'draft',
        ]);

        return $draft;
    }

    private function groupRelatedItems(Collection $items): array
    {
        // Simple grouping: PRs stand alone, commits grouped by date
        $groups = [];

        $prs = $items->where('type', 'pull_request');
        foreach ($prs as $pr) {
            $groups[] = collect([$pr]);
        }

        $releases = $items->where('type', 'release');
        foreach ($releases as $release) {
            $groups[] = collect([$release]);
        }

        // Group orphan commits (not linked to a PR) by day
        $commits = $items->where('type', 'commit');
        if ($commits->isNotEmpty()) {
            $byDay = $commits->groupBy(fn ($c) => $c->created_on_github_at->format('Y-m-d'));
            foreach ($byDay as $dayCommits) {
                $groups[] = $dayCommits;
            }
        }

        return $groups;
    }

    private function generateDraftForGroup(Collection $sourceItems, BrandVoice $voice, $llm): ?Draft
    {
        $result = $this->callLLM($sourceItems, $voice, $llm);
        if (! $result) {
            return null;
        }

        $firstItem = $sourceItems->first();
        $draft = Draft::create([
            'repository_id' => $firstItem->repository_id,
            'title' => $result['title'],
            'teaser' => $result['teaser'] ?? null,
            'body_markdown' => $result['body'],
            'body_html' => Str::markdown($result['body']),
            'category' => $this->mapCategory($result['category'] ?? 'improved'),
            'status' => 'draft',
            'confidence_score' => $result['confidence'] ?? 0.7,
            'source_bundle' => $sourceItems->map(fn ($i) => [
                'id' => $i->id,
                'type' => $i->type,
                'title' => $i->title,
            ])->toArray(),
            'brand_voice_id' => $voice->id,
        ]);

        // Attach source items
        $draft->sourceItems()->attach($sourceItems->pluck('id'));

        return $draft;
    }

    private function callLLM(Collection $sourceItems, BrandVoice $voice, $llm): ?array
    {
        $language = $voice->language ?? 'de';
        $languageInstruction = $this->getLanguageInstruction($language);

        $voiceProfile = $voice->generated_profile;
        $voiceInstruction = '';
        if ($voiceProfile) {
            $voiceInstruction = "\n\nBrand Voice Profile:\n".json_encode($voiceProfile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } elseif ($voice->sample_texts) {
            $samples = implode("\n---\n", $voice->sample_texts);
            $voiceInstruction = "\n\nBrand Voice Samples (match this tone and style):\n{$samples}";
        }

        $noGoWords = $voice->no_go_words ? "\n\nNever use these words: {$voice->no_go_words}" : '';

        $itemsContext = $sourceItems->map(function (SourceItem $item) {
            $metadata = $item->metadata ?? [];
            $files = implode(', ', array_slice($metadata['changed_files'] ?? [], 0, 15));

            return "Type: {$item->type}\n"
                ."Title: {$item->title}\n"
                ."Description: ".mb_substr($item->body ?? '', 0, 1500)."\n"
                ."Files changed: {$files}\n"
                ."Labels: ".implode(', ', $metadata['labels'] ?? []);
        })->implode("\n\n---\n\n");

        $systemPrompt = <<<PROMPT
You are a product update writer. Your job is to transform technical GitHub changes into a clear, user-friendly product update.

{$languageInstruction}
{$voiceInstruction}
{$noGoWords}

Rules:
- Write for end users, not developers
- Focus on the benefit, not the technical implementation
- Never invent features not supported by the source material
- Never include technical jargon unless it's commonly understood
- Keep it concise: 1-3 short paragraphs maximum
- When in doubt, be conservative about what to claim
- Do NOT include any secrets, tokens, API keys, or credentials
- Do NOT use generic AI phrases like "we're excited to announce" or "we've been working hard"

Respond with a JSON object:
- "title": short, catchy title (max 80 characters)
- "teaser": one-sentence summary (max 160 characters)
- "body": markdown-formatted update text (1-3 paragraphs)
- "category": one of "new", "improved", "fixed", "performance", "security"
- "confidence": 0.0 to 1.0 (how confident you are this is accurate)

Return ONLY valid JSON, no markdown formatting.
PROMPT;

        $response = $llm->chat([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "Generate a product update from these changes:\n\n{$itemsContext}"],
        ], ['temperature' => 0.4, 'max_tokens' => 2048]);

        $result = json_decode($this->cleanJsonResponse($response), true);

        if (! is_array($result) || empty($result['title']) || empty($result['body'])) {
            return null;
        }

        return $result;
    }

    private function getLanguageInstruction(string $language): string
    {
        return match ($language) {
            'de' => 'Write the update in German (Deutsch). Use "du" unless the brand voice says otherwise.',
            'en' => 'Write the update in English.',
            'fr' => 'Write the update in French (Français).',
            'es' => 'Write the update in Spanish (Español).',
            'it' => 'Write the update in Italian (Italiano).',
            'nl' => 'Write the update in Dutch (Nederlands).',
            'pt' => 'Write the update in Portuguese (Português).',
            'pl' => 'Write the update in Polish (Polski).',
            'ja' => 'Write the update in Japanese (日本語).',
            default => "Write the update in the language identified by code: {$language}.",
        };
    }

    private function mapCategory(string $category): string
    {
        return match (strtolower($category)) {
            'new', 'feature' => 'new',
            'improved', 'improvement', 'enhancement' => 'improved',
            'fixed', 'fix', 'bug', 'bugfix' => 'fixed',
            'performance', 'speed' => 'performance',
            'security' => 'security',
            default => 'improved',
        };
    }

    private function cleanJsonResponse(string $response): string
    {
        $response = trim($response);

        if (str_starts_with($response, '```')) {
            $response = preg_replace('/^```(?:json)?\s*/m', '', $response);
            $response = preg_replace('/\s*```\s*$/m', '', $response);
        }

        return trim($response);
    }
}
