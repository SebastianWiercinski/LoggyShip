<?php

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

// Setup Wizard (only accessible when not installed)
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

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (require auth + installation)
Route::middleware(['auth', EnsureInstalled::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Placeholder routes - will be implemented in later phases
    Route::get('/drafts', function () { return view('admin.drafts.index'); })->name('drafts.index');
    Route::get('/posts', function () { return view('admin.drafts.index'); })->name('posts.index');
    Route::get('/repositories', function () { return view('admin.drafts.index'); })->name('repositories.index');
    Route::get('/brand-voices', function () { return view('admin.drafts.index'); })->name('brand-voices.index');
    Route::get('/settings', function () { return view('admin.drafts.index'); })->name('settings.index');
});

// Public changelog routes
Route::prefix('updates')->group(function () {
    Route::get('/', function () { return 'Changelog coming soon.'; })->name('public.changelog');
    Route::get('/feed.xml', function () { return response('', 200)->header('Content-Type', 'application/xml'); })->name('public.feed');
});
