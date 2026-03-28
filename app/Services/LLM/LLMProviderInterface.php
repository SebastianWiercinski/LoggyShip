<?php

namespace App\Services\LLM;

interface LLMProviderInterface
{
    public function chat(array $messages, array $options = []): string;
}
