<?php

namespace App\Services\AI;

use App\Models\SourceItem;
use App\Services\LLM\LLMFactory;
use Illuminate\Support\Collection;

class RelevanceService
{
    public function __construct(
        private LLMFactory $llmFactory,
        private RulesEngine $rulesEngine,
    ) {}

    public function classifyItems(Collection $sourceItems): int
    {
        $classified = 0;

        // First pass: rule-based filtering
        foreach ($sourceItems as $item) {
            if ($this->rulesEngine->shouldExclude($item)) {
                $item->update([
                    'is_user_facing' => false,
                    'is_excluded' => true,
                    'relevance_score' => 0.0,
                    'classification_reason' => 'Excluded by rules engine',
                ]);
                $classified++;
            }
        }

        // Second pass: LLM-based classification for remaining items
        $remaining = $sourceItems->filter(fn ($item) => $item->is_user_facing === null && ! $item->is_excluded);

        if ($remaining->isEmpty()) {
            return $classified;
        }

        // Process in batches
        foreach ($remaining->chunk(10) as $batch) {
            $classified += $this->classifyBatch($batch);
        }

        return $classified;
    }

    private function classifyBatch(Collection $items): int
    {
        $llm = $this->llmFactory->make();

        $itemDescriptions = $items->map(function (SourceItem $item, int $index) {
            $metadata = $item->metadata ?? [];
            $files = implode(', ', array_slice($metadata['changed_files'] ?? [], 0, 20));
            $labels = implode(', ', $metadata['labels'] ?? []);

            return "ITEM {$index}:\n"
                ."Type: {$item->type}\n"
                ."Title: {$item->title}\n"
                ."Body: ".mb_substr($item->body ?? '', 0, 500)."\n"
                ."Files: {$files}\n"
                ."Labels: {$labels}\n"
                ."Branch: ".($metadata['head_branch'] ?? 'n/a');
        })->implode("\n\n---\n\n");

        $systemPrompt = <<<'PROMPT'
You are a software change classifier. For each item, determine:
1. Is this change user-facing (visible or beneficial to end users)?
2. What category does it belong to? (feature, improvement, fix, performance, security, internal)
3. How confident are you? (0.0 to 1.0)

Rules:
- Changes to UI, API behavior, performance, or user-visible functionality are user-facing
- Refactors, CI changes, dependency updates, test-only changes, and documentation are NOT user-facing
- When unsure, lean toward NOT user-facing (conservative approach)

Respond with a JSON array. Each element must have:
- "index": the ITEM number
- "user_facing": true or false
- "category": one of "feature", "improvement", "fix", "performance", "security", "internal"
- "confidence": 0.0 to 1.0
- "reason": brief explanation (1 sentence)

Return ONLY valid JSON, no markdown formatting.
PROMPT;

        try {
            $response = $llm->chat([
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $itemDescriptions],
            ], ['temperature' => 0.1]);

            $results = json_decode($this->cleanJsonResponse($response), true);

            if (! is_array($results)) {
                return 0;
            }

            $classified = 0;
            foreach ($results as $result) {
                $index = $result['index'] ?? null;
                if ($index === null || ! isset($items->values()[$index])) {
                    continue;
                }

                $item = $items->values()[$index];
                $item->update([
                    'is_user_facing' => $result['user_facing'] ?? false,
                    'relevance_score' => $result['confidence'] ?? 0.5,
                    'classification_reason' => $result['reason'] ?? 'AI classified',
                ]);
                $classified++;
            }

            return $classified;
        } catch (\Exception $e) {
            \Log::warning('LLM classification failed: '.$e->getMessage());

            return 0;
        }
    }

    private function cleanJsonResponse(string $response): string
    {
        $response = trim($response);

        // Strip markdown code fences
        if (str_starts_with($response, '```')) {
            $response = preg_replace('/^```(?:json)?\s*/m', '', $response);
            $response = preg_replace('/\s*```\s*$/m', '', $response);
        }

        return trim($response);
    }
}
