<?php

use App\Http\Controllers\Admin\BrandVoiceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DraftController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReleaseNoteController;
use App\Http\Controllers\Admin\RepositoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Setup\SetupController;
use App\Http\Middleware\EnsureInstalled;
use App\Http\Middleware\EnsureNotInstalled;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    if (! app(\App\Services\SettingsService::class)->get('general', 'installed')) {
        return redirect()->route('setup.welcome');
    }

    return redirect()->route('admin.dashboard');
});

// Setup Wizard
Route::middleware(EnsureNotInstalled::class)->prefix('install')->group(function () {
    Route::get('/', [SetupController::class, 'welcome'])->name('setup.welcome');
    Route::post('/', [SetupController::class, 'welcomeStore'])->name('setup.welcome.store');
    Route::get('/system-check', [SetupController::class, 'systemCheck'])->name('setup.system-check');
    Route::post('/system-check', [SetupController::class, 'systemCheckStore'])->name('setup.system-check.store');
    Route::get('/database', [SetupController::class, 'database'])->name('setup.database');
    Route::post('/database', [SetupController::class, 'databaseStore'])->name('setup.database.store');
    Route::get('/admin', [SetupController::class, 'admin'])->name('setup.admin');
    Route::post('/admin', [SetupController::class, 'adminStore'])->name('setup.admin.store');
    Route::get('/llm', [SetupController::class, 'llm'])->name('setup.llm');
    Route::post('/llm', [SetupController::class, 'llmStore'])->name('setup.llm.store');
    Route::get('/brand-voice', [SetupController::class, 'brandVoice'])->name('setup.brand-voice');
    Route::post('/brand-voice', [SetupController::class, 'brandVoiceStore'])->name('setup.brand-voice.store');
    Route::get('/github', [SetupController::class, 'github'])->name('setup.github');
    Route::post('/github', [SetupController::class, 'githubStore'])->name('setup.github.store');
    Route::get('/repository', [SetupController::class, 'repository'])->name('setup.repository');
    Route::post('/repository', [SetupController::class, 'repositoryStore'])->name('setup.repository.store');
    Route::get('/public-site', [SetupController::class, 'publicSite'])->name('setup.public-site');
    Route::post('/public-site', [SetupController::class, 'publicSiteStore'])->name('setup.public-site.store');
    Route::get('/finish', [SetupController::class, 'finish'])->name('setup.finish');
    Route::post('/finish', [SetupController::class, 'finishStore'])->name('setup.finish.store');
});

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['auth', EnsureInstalled::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Release Notes
    Route::get('/release-notes/create', [ReleaseNoteController::class, 'create'])->name('release-notes.create');
    Route::post('/release-notes/generate', [ReleaseNoteController::class, 'generate'])->name('release-notes.generate');

    // Drafts
    Route::get('/drafts', [DraftController::class, 'index'])->name('drafts.index');
    Route::get('/drafts/{draft}', [DraftController::class, 'show'])->name('drafts.show');
    Route::get('/drafts/{draft}/edit', [DraftController::class, 'edit'])->name('drafts.edit');
    Route::put('/drafts/{draft}', [DraftController::class, 'update'])->name('drafts.update');
    Route::post('/drafts/{draft}/publish', [DraftController::class, 'publish'])->name('drafts.publish');
    Route::post('/drafts/{draft}/discard', [DraftController::class, 'discard'])->name('drafts.discard');
    Route::post('/drafts/{draft}/regenerate', [DraftController::class, 'regenerate'])->name('drafts.regenerate');

    // Posts
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/unpublish', [PostController::class, 'unpublish'])->name('posts.unpublish');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Repositories
    Route::get('/repositories', [RepositoryController::class, 'index'])->name('repositories.index');
    Route::post('/repositories/{repository}/toggle', [RepositoryController::class, 'toggleActive'])->name('repositories.toggle');
    Route::post('/repositories/{repository}/sync', [RepositoryController::class, 'sync'])->name('repositories.sync');
    Route::put('/repositories/{repository}/settings', [RepositoryController::class, 'updateSettings'])->name('repositories.settings');

    // Brand Voices
    Route::get('/brand-voices', [BrandVoiceController::class, 'index'])->name('brand-voices.index');
    Route::get('/brand-voices/{brandVoice}/edit', [BrandVoiceController::class, 'edit'])->name('brand-voices.edit');
    Route::put('/brand-voices/{brandVoice}', [BrandVoiceController::class, 'update'])->name('brand-voices.update');
    Route::post('/brand-voices/{brandVoice}/analyze', [BrandVoiceController::class, 'analyze'])->name('brand-voices.analyze');
    Route::post('/brand-voices/{brandVoice}/activate', [BrandVoiceController::class, 'setActive'])->name('brand-voices.activate');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/llm', [SettingsController::class, 'updateLLM'])->name('settings.llm');
    Route::post('/settings/github', [SettingsController::class, 'updateGitHub'])->name('settings.github');
    Route::post('/settings/site', [SettingsController::class, 'updateSite'])->name('settings.site');
    Route::post('/settings/rules', [SettingsController::class, 'updateRules'])->name('settings.rules');
});

// Public changelog
Route::prefix('updates')->group(function () {
    Route::get('/', [\App\Http\Controllers\Public\ChangelogController::class, 'index'])->name('public.changelog');
    Route::get('/feed.xml', [\App\Http\Controllers\Public\ChangelogController::class, 'feed'])->name('public.feed');
    Route::get('/{post:slug}', [\App\Http\Controllers\Public\ChangelogController::class, 'show'])->name('public.changelog.show');
});
