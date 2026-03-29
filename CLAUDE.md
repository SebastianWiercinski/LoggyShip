# CLAUDE.md — LoggyShip Agent Guide

## What is LoggyShip?

Self-hosted Laravel 12 app that turns GitHub activity (commits, PRs, releases) into user-facing product updates via AI. Runs on shared PHP hosting with SQLite. Supports Anthropic Claude and Google Gemini as LLM providers.

## Tech Stack

- **Backend:** PHP 8.2+ / Laravel 12
- **Database:** SQLite (default, WAL mode) or MySQL
- **Frontend:** Tailwind CSS v4 + Vite 7 + Alpine.js (CDN)
- **Templates:** Blade components (server-rendered, no SPA)
- **LLM:** Anthropic Messages API / Gemini generateContent API

## Quick Commands

```bash
# Development
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate
php artisan serve

# Artisan commands
php artisan loggyship:sync              # Sync GitHub repos
php artisan loggyship:sync --repo=1     # Sync specific repo
php artisan loggyship:generate          # Classify items + generate drafts
php artisan loggyship:generate --classify  # Only classify, no drafts

# Build & test
npm run build                           # Vite production build
php artisan test                        # Run tests
```

## Architecture

```
app/
├── Console/Commands/
│   ├── GenerateDrafts.php          # loggyship:generate — classify + draft + auto-publish
│   └── SyncRepositories.php        # loggyship:sync — pull GitHub data
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php    # GET /admin (invokable)
│   │   │   ├── DraftController.php        # CRUD + publish/discard/regenerate
│   │   │   ├── PostController.php         # CRUD + unpublish + manual creation
│   │   │   ├── RepositoryController.php   # toggle/sync/settings
│   │   │   ├── BrandVoiceController.php   # edit/analyze/activate
│   │   │   └── SettingsController.php     # LLM/GitHub/Site/Rules forms
│   │   ├── Auth/LoginController.php       # login/logout
│   │   ├── Public/ChangelogController.php # /updates, /updates/{slug}, RSS feed
│   │   └── Setup/SetupController.php      # 10-step install wizard
│   └── Middleware/
│       ├── EnsureInstalled.php     # Redirects to setup if not installed
│       └── EnsureNotInstalled.php  # Redirects to admin if already installed
├── Models/
│   ├── User.php                    # Auth user (is_admin flag)
│   ├── Setting.php                 # Key-value settings (group+key unique)
│   ├── Repository.php              # GitHub repos → has many SourceItems, Drafts, Posts
│   ├── SourceItem.php              # Commits/PRs/releases → belongs to many Drafts
│   ├── BrandVoice.php              # Voice profiles → has many Drafts
│   ├── Draft.php                   # AI-generated drafts → has one Post
│   └── Post.php                    # Published updates (route key: slug)
├── Providers/
│   └── AppServiceProvider.php      # Registers SettingsService as singleton
└── Services/
    ├── SettingsService.php         # Encrypted key-value store with 5-min cache
    ├── AI/
    │   ├── RelevanceService.php    # Two-pass: rules → LLM classification (batches of 10)
    │   ├── DraftGeneratorService.php # Groups items → LLM generates title/teaser/body/category
    │   ├── BrandVoiceAnalyzer.php  # Analyzes sample texts → structured voice profile
    │   └── RulesEngine.php         # Include/exclude paths, labels, branch patterns
    ├── GitHub/
    │   ├── GitHubClient.php        # Paginated API (max 5 pages, 30s timeout)
    │   └── GitHubSyncService.php   # Orchestrates commit/PR/release sync
    └── LLM/
        ├── LLMProviderInterface.php # chat(messages, options): string
        ├── LLMFactory.php          # Resolves provider from settings
        ├── AnthropicProvider.php   # api.anthropic.com/v1/messages
        └── GeminiProvider.php      # generativelanguage.googleapis.com
```

## Data Flow

```
GitHub API → GitHubSyncService → SourceItem (raw data)
                                      ↓
                              RulesEngine (exclude paths/labels)
                                      ↓
                              RelevanceService (LLM classification)
                                      ↓
                              DraftGeneratorService (LLM → Draft)
                                      ↓
                         Admin review OR auto-publish → Post
                                      ↓
                              ChangelogController → public page + RSS
```

## Database Schema

| Table | Key Columns | Notes |
|---|---|---|
| `settings` | group, key, value, encrypted | Unique on (group, key) |
| `repositories` | github_id, full_name, sync_* flags | Unique on github_id |
| `source_items` | repository_id, type, github_id, is_user_facing | Unique on (repo, type, github_id) |
| `brand_voices` | sample_texts (JSON), generated_profile (JSON), language | One active at a time |
| `drafts` | title, body_markdown, category, status, confidence_score | Status: draft/review/published/discarded |
| `draft_source_item` | draft_id, source_item_id | Many-to-many pivot |
| `posts` | slug (unique), body_html, is_published, published_at | Route key is slug |

## Routes Summary

| Area | Prefix | Middleware | Key Routes |
|---|---|---|---|
| Setup Wizard | `/install` | EnsureNotInstalled | 10 GET/POST step pairs |
| Auth | `/login`, `/logout` | — | Login form + POST |
| Admin | `/admin` | auth, EnsureInstalled | Dashboard, Drafts, Posts, Repos, Brand Voices, Settings |
| Public | `/updates` | — | Changelog index, post detail, RSS feed.xml |
| Root | `/` | — | Redirects to setup or admin |

## Settings Reference

Settings are stored encrypted in the `settings` table via `SettingsService`.

| Group | Key | Type | Default |
|---|---|---|---|
| general | installed | bool | false |
| general | language | string | 'de' |
| general | auto_publish | bool | false |
| general | publish_frequency | string | 'realtime' |
| general | last_generate_run | ISO8601 | null |
| llm | provider | string | 'anthropic' |
| llm | model | string | '' |
| llm | api_key | encrypted | — |
| github | pat | encrypted | — |
| github | username | string | — |
| site | name | string | 'LoggyShip' |
| site | accent_color | hex | '#6366f1' |
| site | seo_title | string | '' |
| site | seo_description | string | '' |
| rules | exclude_paths | newline-sep | '' |
| rules | exclude_labels | comma-sep | '' |
| rules | include_labels | comma-sep | '' |

## Scheduling (routes/console.php)

- `loggyship:sync` runs **hourly**
- `loggyship:generate` runs based on `publish_frequency` setting:
  - `realtime`: every minute
  - `daily`: every 24h
  - `every_3_days`: every 72h
  - `weekly`: every 7 days
  - `biweekly`: every 14 days
  - `monthly`: every 30 days

## Frontend / Views

Blade component layouts in `resources/views/components/layouts/`:
- `app.blade.php` — Admin layout (dark sidebar, Alpine.js mobile menu)
- `public.blade.php` — Public changelog layout (clean, editorial)
- `setup.blade.php` — Setup wizard layout (centered card with progress bar)

Key components:
- `x-nav-link` — Sidebar nav item with active state
- `x-category-badge` — Colored badge (new/improved/fixed/performance/security)

CSS: Tailwind v4 with `@theme` custom properties, custom animations, glass morphism. Built via `@tailwindcss/vite` plugin. No `tailwind.config.js` — all config in `app.css`.

## Deployment

**GitHub Actions** (`.github/workflows/deploy.yml`):
1. `composer install --no-dev` + `npm ci` + `npm run build`
2. FTP upload via `lftp` to shared hosting
3. Secrets in GitHub Environment "DCS Studio KAS FTP": `FTP_HOST`, `FTP_USER`, `FTP_PASS`

**Post-deploy** (via `public/install.php` web installer if no SSH):
- Creates SQLite database
- Runs migrations
- Sets APP_URL
- Self-deletes after success

**Requirements on server:** PHP 8.2+, sqlite3/pdo_sqlite extensions, writable storage/ and database/

## Code Conventions

- **No SPA** — all server-rendered Blade
- **No Livewire/Inertia** — plain forms with POST/PUT
- **Services layer** — business logic in `app/Services/`, controllers stay thin
- **Encrypted settings** — API keys stored via `Crypt::encryptString()`
- **LLM abstraction** — `LLMProviderInterface` with factory pattern
- **Categories:** new, improved, fixed, performance, security
- **Draft statuses:** draft, review, published, discarded
- **SQLite defaults** — WAL mode, busy_timeout=5000, synchronous=NORMAL

## Security Notes

- API keys and PATs are encrypted in database (never in `.env` or git)
- `public/install.php` self-deletes after use
- No credentials in repository — FTP creds in GitHub Secrets only
- CSRF protection on all forms
- Admin routes behind `auth` middleware
