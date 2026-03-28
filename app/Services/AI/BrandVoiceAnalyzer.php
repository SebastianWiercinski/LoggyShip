<?php

namespace App\Services\AI;

use App\Models\BrandVoice;
use App\Services\LLM\LLMFactory;

class BrandVoiceAnalyzer
{
    public function __construct(private LLMFactory $llmFactory) {}

    public function analyze(BrandVoice $brandVoice): array
    {
        $samples = $brandVoice->sample_texts ?? [];

        if (empty($samples)) {
            return $this->defaultProfile($brandVoice->language ?? 'de');
        }

        $llm = $this->llmFactory->make();

        $samplesText = implode("\n\n---\n\n", $samples);
        $language = $brandVoice->language ?? 'de';
        $noGoWords = $brandVoice->no_go_words ?? '';

        $systemPrompt = <<<'PROMPT'
You are a brand voice analyst. Analyze the given text samples and extract a structured brand voice profile.

Respond with a JSON object containing:
- "voice_summary": 2-3 sentence description of the overall voice
- "tone": one of "casual", "professional", "friendly", "formal", "playful", "direct"
- "formality": one of "du" (informal), "Sie" (formal), "you" (English informal), "neutral"
- "do_rules": array of 3-5 things the writing should do
- "dont_rules": array of 3-5 things the writing should avoid
- "vocabulary_preferences": array of preferred words/phrases
- "sentence_style": "short", "medium", or "long"

Return ONLY valid JSON, no markdown formatting.
PROMPT;

        $userMessage = "Analyze these brand text samples (language: {$language}):\n\n{$samplesText}";
        if ($noGoWords) {
            $userMessage .= "\n\nThe brand explicitly wants to avoid these words: {$noGoWords}";
        }

        try {
            $response = $llm->chat([
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userMessage],
            ], ['temperature' => 0.2]);

            $profile = json_decode($this->cleanJsonResponse($response), true);

            if (is_array($profile) && ! empty($profile['voice_summary'])) {
                return $profile;
            }
        } catch (\Exception $e) {
            \Log::warning('Brand voice analysis failed: '.$e->getMessage());
        }

        return $this->defaultProfile($language);
    }

    public function analyzeAndSave(BrandVoice $brandVoice): BrandVoice
    {
        $profile = $this->analyze($brandVoice);
        $brandVoice->update(['generated_profile' => $profile]);

        return $brandVoice;
    }

    private function defaultProfile(string $language): array
    {
        $isGerman = in_array($language, ['de']);

        return [
            'voice_summary' => $isGerman
                ? 'Direkter, freundlicher Ton. Kurze Sätze. Nutzen statt Technik.'
                : 'Direct, friendly tone. Short sentences. Focus on benefits, not implementation.',
            'tone' => 'friendly',
            'formality' => $isGerman ? 'du' : 'you',
            'do_rules' => $isGerman
                ? ['Kurze Sätze verwenden', 'Nutzen für den User betonen', 'Konkret sein']
                : ['Use short sentences', 'Highlight user benefit', 'Be specific'],
            'dont_rules' => $isGerman
                ? ['Keine Buzzwords', 'Kein LLM-Sprech', 'Keine vagen Aussagen']
                : ['No buzzwords', 'No AI-sounding phrases', 'No vague statements'],
            'vocabulary_preferences' => [],
            'sentence_style' => 'short',
        ];
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
