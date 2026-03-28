<?php

namespace App\Services\LLM;

use App\Services\SettingsService;

class LLMFactory
{
    public function __construct(private SettingsService $settings) {}

    public function make(?string $provider = null, ?string $model = null, ?string $apiKey = null): LLMProviderInterface
    {
        $provider = $provider ?? $this->settings->get('llm', 'provider', 'anthropic');
        $model = $model ?? $this->settings->get('llm', 'model', 'claude-sonnet-4-20250514');
        $apiKey = $apiKey ?? $this->settings->get('llm', 'api_key');

        if (! $apiKey) {
            throw new \RuntimeException('No LLM API key configured. Please set one in Settings.');
        }

        return match ($provider) {
            'anthropic' => new AnthropicProvider($apiKey, $model),
            'gemini' => new GeminiProvider($apiKey, $model),
            default => throw new \RuntimeException("Unknown LLM provider: {$provider}"),
        };
    }
}
