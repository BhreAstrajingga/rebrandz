# Version Control
## 0001-multi-tenancy-v1.1.0-major
### [update]
[] update ...
[] update ...
[] dan seterusnya

### [new_features/whats-new]
[] new feature ...
[] new feature ...
[] dan seterusnya

###  [breaking_changes]
[] changed ...
[] changed ...
[] dan seterusnya

## 0001-multi-tenancy-v1.1.0-major
### [update]
[] update ...
[] update ...
[] dan seterusnya

### [new_features/whats-new]
[] new feature ...
[] new feature ...
[] dan seterusnya

###  [breaking_changes]
[] changed ...
[] changed ...
[] dan seterusnya


## 0001-multi-tenancy-v1.1.0-major
### [update]
- Remove legacy custom tenancy artifacts (providers, middleware, helpers, traits, pages)
- Clean AppServiceProvider: removed CurrentTenant binding
- Kept DB schema intact; official Filament tenancy will be reintroduced
- Updated AGENTS.md: Slugs, Git hooks & Version workflow, Version Control Log, Tenancy Roles & Permissions
- Implemented slug automation and fallbacks in User, Customer, Service, Post models

### [new_features/whats-new]
- Added .githooks/pre-push to validate branch naming
- Added VERSION and bin/next-version helper for SemVer
- Added backfill migrations for users, services, posts slugs
- Exposed slug columns in Filament (Customers, Services) tables

###  [breaking_changes]
- Removed custom tenancy panel/provider and tenancy middleware stack
## 0001-multi-tenancy-v1.1.0-major
### [update]
- Remove tenancy migrations: 2025_10_16_000100_create_tenants_table.php, 2025_10_16_000200_add_tenant_id_to_users_table.php
- Keep cleanup migration 2025_10_19_020943_drop_tenancy_tables_and_columns.php to ensure environments drop artifacts

### [new_features/whats-new]
- N/A

###  [breaking_changes]
- N/A

## 0123-business-categories-v1.2.0-major
- Add business categories migration and model
- Ensure slugs follow Post::generateUniqueSlug() pattern
- No breaking changes; tenant scope unchanged

## 0123-business-categories-v1.2.0-major
- Add BusinessCategorySeeder with canonical categories list
- Wire seeder into DatabaseSeeder (runs after Users)
- Ensure slug uniqueness for categories

