<?php

namespace App\Console\Commands;

use App\Models\BrandVoice;
use App\Models\SourceItem;
use App\Services\AI\DraftGeneratorService;
use App\Services\AI\RelevanceService;
use Illuminate\Console\Command;

class GenerateDrafts extends Command
{
    protected $signature = 'loggyship:generate
        {--classify : Only classify items, do not generate drafts}
        {--repo= : Specific repository ID}';

    protected $description = 'Classify source items and generate draft product updates';

    public function handle(RelevanceService $relevanceService, DraftGeneratorService $draftGenerator): int
    {
        // Step 1: Classify unclassified items
        $query = SourceItem::whereNull('is_user_facing')->where('is_excluded', false);

        if ($repoId = $this->option('repo')) {
            $query->where('repository_id', $repoId);
        }

        $unclassified = $query->get();

        if ($unclassified->isNotEmpty()) {
            $this->info("Classifying {$unclassified->count()} source items...");
            $classified = $relevanceService->classifyItems($unclassified);
            $this->info("  Classified: {$classified}");
        } else {
            $this->info('No unclassified items.');
        }

        if ($this->option('classify')) {
            return self::SUCCESS;
        }

        // Step 2: Generate drafts for user-facing items not yet linked to a draft
        $userFacingItems = SourceItem::where('is_user_facing', true)
            ->whereDoesntHave('drafts')
            ->when($this->option('repo'), fn ($q) => $q->where('repository_id', $this->option('repo')))
            ->orderBy('created_on_github_at', 'desc')
            ->limit(20)
            ->get();

        if ($userFacingItems->isEmpty()) {
            $this->info('No new user-facing items to generate drafts for.');

            return self::SUCCESS;
        }

        $voice = BrandVoice::where('is_active', true)->first();
        if (! $voice) {
            $this->error('No active brand voice found. Please configure one first.');

            return self::FAILURE;
        }

        $this->info("Generating drafts for {$userFacingItems->count()} items...");
        $drafts = $draftGenerator->generateDrafts($userFacingItems, $voice);
        $this->info("  Generated {$drafts->count()} drafts.");

        return self::SUCCESS;
    }
}
