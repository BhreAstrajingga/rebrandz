# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

Project commands
- Install deps
  ```bash path=null start=null
  composer install
  npm install
  ```
- Local dev (PHP server, queue worker, Vite dev server) — runs all concurrently
  ```bash path=null start=null
  composer run dev
  ```
  Or run separately:
  ```bash path=null start=null
  php artisan serve
  php artisan queue:listen --tries=1
  npm run dev
  ```
- Build frontend assets
  ```bash path=null start=null
  npm run build
  ```
- Database migration (safe mode used by project scripts)
  ```bash path=null start=null
  php artisan migrate --graceful
  ```
- Tests
  ```bash path=null start=null
  # All tests
  php artisan test

  # Single file
  php artisan test tests/Feature/ExampleTest.php

  # Filter by test name
  php artisan test --filter=YourTestMethodName
  ```
- Lint/format PHP (Laravel Pint)
  ```bash path=null start=null
  vendor/bin/pint --dirty
  ```
- Versioning helper and Git hook
  ```bash path=null start=null
  # Suggest next SemVer based on VERSION file
  php bin/next-version {breaking|major|patch}

  # Enable provided pre-push hook (branch-name validator)
  git config core.hooksPath .githooks_disabled
  ```

Architecture overview
- Framework and key libs
  - Laravel 12 app with Vite + Tailwind CSS v4 for assets.
  - Filament v4 provides server-driven UI via two panels: an Admin panel and a User (subscriber) panel.
  - PHPUnit (phpunit.xml uses in-memory SQLite) with tests in tests/Feature and tests/Unit.
- Panels and routing
  - Providers registered in bootstrap/providers.php:
    - App\Providers\Filament\AdminPanelProvider: id="admin", path="/admin"; discovers resources/pages/widgets; no login page (central auth pages used).
    - App\Providers\Filament\UserPanelProvider: id="user", path="/user"; pages for subscriber flows (Pricing, Billing, Apps Settings, Tenant Overview, etc.).
  - Central auth routes in routes/web.php map /login, /register, and password reset to Filament pages.
- Multi-tenancy model (runtime off for now)
  - TENANCY.md defines the intended subdomain-based, DB-isolated tenancy model (per-tenant DB/schema).
  - Runtime tenant resolution middleware is currently disabled in bootstrap/app.php, but config/tenancy.php defines base_domain and excluded subdomains.
  - Models already reflect tenant concepts (e.g., User can implement tenant access; Tenant has slug/name/domain/owner; add-on relations exist).
- Domain models and conventions
  - Slug-first entities with guardrails: User, Tenant, Service auto-generate unique slugs in model boot hooks (see App\Models\{User,Tenant,Service}).
  - Subscriptions: App\Models\UserSubscription links customers/tenants to services and plans; status derived in Api\SubscriptionController.
  - Services and plans: App\Models\Service has many ServicePlan; ServicePlan casts features to array and is referenced by subscriptions.
  - Feature resolution: App\Services\FeatureResolver merges plan features with tenant add-on codes for a given service.
- Filament resources and pages
  - Admin panel resources:
    - Services: app/Filament/Resources/Services/ServiceResource.php with form/table/infolist split under Schemas/Tables.
    - Service Plans: app/Filament/Resources/ServicePlans/ServicePlanResource.php with pages for list/create/view/edit/management.
    - User Subscriptions: app/Filament/Resources/UserSubscriptions/UserSubscriptionResource.php for CRUD and listings.
  - User panel pages and blades under resources/views/filament/pages/* for subscriber dashboard, pricing, billing, etc.

Important project rules (from AGENTS.md)
- Slugs: Keep stable, unique slugs for URL-exposed models; generate in model lifecycle; mirror Post example patterns.
- Branch/version workflow: Branch names must follow {number}-{feature}-vX.Y.Z[-alpha|-beta|-rc]-{breaking|major|minor|patch}. VERSION file holds current SemVer; use php bin/next-version to compute next. A pre-push hook exists in .githooks_disabled — enable with git config core.hooksPath .githooks_disabled.
- Testing: Use PHPUnit via php artisan test; prefer running the minimal affected scope (file or --filter) during development.
- Formatting: Run vendor/bin/pint --dirty before finalizing changes; don’t use --test.
- Frontend bundling: If assets don’t show up, run npm run build or npm run dev (or composer run dev) and retry.

MCP servers and tools
- This repo defines MCP servers in .vscode/mcp.json:
  - laravel-boost (php artisan boost:mcp): Laravel-aware tools (docs search, tinker, database, browser logs, etc.). Prefer search-docs before coding changes.
  - herd: local URL/context utilities for Laravel Herd.
