<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Models\Post;
use App\Models\Repository;
use App\Models\SourceItem;
use App\Services\SettingsService;

class DashboardController extends Controller
{
    public function __invoke(SettingsService $settings)
    {
        return view('admin.dashboard', [
            'openDrafts' => Draft::whereIn('status', ['draft', 'review'])->count(),
            'publishedPosts' => Post::where('is_published', true)->count(),
            'sourceItems' => SourceItem::count(),
            'lastSync' => Repository::max('last_synced_at'),
            'llmProvider' => $settings->get('llm', 'provider', 'not configured'),
            'llmModel' => $settings->get('llm', 'model', 'not configured'),
        ]);
    }
}
