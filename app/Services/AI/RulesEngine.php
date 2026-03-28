<?php

namespace App\Services\AI;

use App\Models\SourceItem;
use App\Services\SettingsService;

class RulesEngine
{
    private array $excludePaths = [
        'docs/', 'test/', 'tests/', 'spec/', '.github/', '.circleci/',
        'infra/', 'terraform/', 'docker/', '.docker/',
    ];

    private array $excludeFiles = [
        'package-lock.json', 'composer.lock', 'yarn.lock', 'pnpm-lock.yaml',
        '.gitignore', '.editorconfig', '.eslintrc', '.prettierrc',
        'Dockerfile', 'docker-compose.yml', '.env.example',
    ];

    private array $excludeLabels = ['internal', 'chore', 'ci', 'dependencies', 'dependabot'];

    private array $includeLabels = ['user-facing', 'feature', 'enhancement', 'bug', 'fix', 'security'];

    public function __construct(private SettingsService $settings)
    {
        $this->loadCustomRules();
    }

    public function shouldExclude(SourceItem $item): bool
    {
        $metadata = $item->metadata ?? [];

        // Check labels for explicit include
        $labels = $metadata['labels'] ?? [];
        foreach ($labels as $label) {
            if (in_array(strtolower($label), $this->includeLabels)) {
                return false;
            }
        }

        // Check labels for explicit exclude
        foreach ($labels as $label) {
            if (in_array(strtolower($label), $this->excludeLabels)) {
                return true;
            }
        }

        // Check branch name patterns
        $headBranch = $metadata['head_branch'] ?? '';
        if (preg_match('/^(dependabot|renovate|chore|ci|docs)\//i', $headBranch)) {
            return true;
        }

        // Check changed files
        $changedFiles = $metadata['changed_files'] ?? [];
        if (! empty($changedFiles) && $this->allFilesExcluded($changedFiles)) {
            return true;
        }

        return false;
    }

    public function getExcludePathsForPrompt(): string
    {
        return implode(', ', $this->excludePaths);
    }

    public function getIncludeLabelsForPrompt(): string
    {
        return implode(', ', $this->includeLabels);
    }

    private function allFilesExcluded(array $files): bool
    {
        foreach ($files as $file) {
            if (! $this->isExcludedFile($file)) {
                return false;
            }
        }

        return true;
    }

    private function isExcludedFile(string $path): bool
    {
        foreach ($this->excludePaths as $excludePath) {
            if (str_starts_with($path, $excludePath)) {
                return true;
            }
        }

        $filename = basename($path);
        if (in_array($filename, $this->excludeFiles)) {
            return true;
        }

        return false;
    }

    private function loadCustomRules(): void
    {
        $customExcludePaths = $this->settings->get('rules', 'exclude_paths');
        if ($customExcludePaths) {
            $this->excludePaths = array_merge(
                $this->excludePaths,
                array_filter(array_map('trim', explode("\n", $customExcludePaths)))
            );
        }

        $customExcludeLabels = $this->settings->get('rules', 'exclude_labels');
        if ($customExcludeLabels) {
            $this->excludeLabels = array_merge(
                $this->excludeLabels,
                array_filter(array_map('trim', explode(',', $customExcludeLabels)))
            );
        }

        $customIncludeLabels = $this->settings->get('rules', 'include_labels');
        if ($customIncludeLabels) {
            $this->includeLabels = array_merge(
                $this->includeLabels,
                array_filter(array_map('trim', explode(',', $customIncludeLabels)))
            );
        }
    }
}
