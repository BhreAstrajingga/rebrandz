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
