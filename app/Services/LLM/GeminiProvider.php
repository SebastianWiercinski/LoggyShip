<?php

namespace App\Services\LLM;

use Illuminate\Support\Facades\Http;

class GeminiProvider implements LLMProviderInterface
{
    public function __construct(
        private string $apiKey,
        private string $model = 'gemini-2.5-flash',
    ) {}

    public function chat(array $messages, array $options = []): string
    {
        $systemInstruction = null;
        $contents = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemInstruction = $message['content'];
            } else {
                $role = $message['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $message['content']]],
                ];
            }
        }

        $body = ['contents' => $contents];

        if ($systemInstruction) {
            $body['systemInstruction'] = [
                'parts' => [['text' => $systemInstruction]],
            ];
        }

        if (isset($options['temperature'])) {
            $body['generationConfig']['temperature'] = $options['temperature'];
        }

        if (isset($options['max_tokens'])) {
            $body['generationConfig']['maxOutputTokens'] = $options['max_tokens'];
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";

        $response = Http::withHeaders([
            'x-goog-api-key' => $this->apiKey,
        ])
            ->timeout(120)
            ->post($url, $body);

        if (! $response->successful()) {
            throw new \RuntimeException('Gemini API error: '.$response->body());
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }
}
