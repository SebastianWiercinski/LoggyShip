<?php

namespace App\Services\AI;

use App\Models\BrandVoice;
use App\Models\SourceItem;

class PromptHelper
{
    public static function getLanguageInstruction(string $language): string
    {
        return match ($language) {
            'de' => 'Write in German (Deutsch). Use "du" unless the brand voice says otherwise.',
            'en' => 'Write in English.',
            'fr' => 'Write in French (Français).',
            'es' => 'Write in Spanish (Español).',
            'it' => 'Write in Italian (Italiano).',
            'nl' => 'Write in Dutch (Nederlands).',
            'pt' => 'Write in Portuguese (Português).',
            'pl' => 'Write in Polish (Polski).',
            'ja' => 'Write in Japanese (日本語).',
            default => "Write in the language identified by code: {$language}.",
        };
    }

    public static function getVoiceInstruction(BrandVoice $voice): string
    {
        if ($voice->generated_profile) {
            return "\n\nBrand Voice Profile:\n".json_encode($voice->generated_profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        if ($voice->sample_texts) {
            $samples = implode("\n---\n", $voice->sample_texts);

            return "\n\nBrand Voice Samples (match this tone and style):\n{$samples}";
        }

        return '';
    }

    public static function getNoGoWordsInstruction(BrandVoice $voice): string
    {
        return $voice->no_go_words ? "\n\nNever use these words: {$voice->no_go_words}" : '';
    }

    public static function formatSourceItemContext(SourceItem $item): string
    {
        $metadata = $item->metadata ?? [];
        $files = implode(', ', array_slice($metadata['changed_files'] ?? [], 0, 15));

        return "Type: {$item->type}\n"
            ."Title: {$item->title}\n"
            ."Description: ".mb_substr($item->body ?? '', 0, 1500)."\n"
            ."Files changed: {$files}\n"
            ."Labels: ".implode(', ', $metadata['labels'] ?? []);
    }

    public static function cleanJsonResponse(string $response): string
    {
        $response = trim($response);

        if (str_starts_with($response, '```')) {
            $response = preg_replace('/^```(?:json)?\s*/m', '', $response);
            $response = preg_replace('/\s*```\s*$/m', '', $response);
        }

        return trim($response);
    }

    public static function mapCategory(string $category): string
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
}
