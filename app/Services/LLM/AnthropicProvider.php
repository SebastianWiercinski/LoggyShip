<?php

namespace App\Services\LLM;

use Illuminate\Support\Facades\Http;

class AnthropicProvider implements LLMProviderInterface
{
    public function __construct(
        private string $apiKey,
        private string $model = 'claude-sonnet-4-20250514',
    ) {}

    public function chat(array $messages, array $options = []): string
    {
        $systemMessage = null;
        $apiMessages = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage = $message['content'];
            } else {
                $apiMessages[] = $message;
            }
        }

        $body = [
            'model' => $this->model,
            'max_tokens' => $options['max_tokens'] ?? 4096,
            'messages' => $apiMessages,
        ];

        if ($systemMessage) {
            $body['system'] = $systemMessage;
        }

        if (isset($options['temperature'])) {
            $body['temperature'] = $options['temperature'];
        }

        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
        ])
            ->timeout(120)
            ->post('https://api.anthropic.com/v1/messages', $body);

        if (! $response->successful()) {
            throw new \RuntimeException('Anthropic API error: '.$response->body());
        }

        $data = $response->json();

        return $data['content'][0]['text'] ?? '';
    }
}
