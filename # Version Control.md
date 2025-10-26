## 0001-multi-tenancy-v1.1.0-major
### [update]
- Fix pre-push hook: validate all pushed refs, accept minor change type

### [new_features/whats-new]
- N/A

###  [breaking_changes]
- N/A
## 0001-multi-tenancy-v1.1.0-major
### [update]
- Scaffold official Filament tenancy baseline: Tenant model/migration, users.tenant_id FK
- Add TenantPanelProvider with ->tenant(Tenant::class, 'slug', 'tenant') and register provider
- Update User to implement HasTenants (canAccessTenant/getTenants) and allow tenant panel
- Fix bootstrap/providers.php formatting

### [new_features/whats-new]
- Tenant slug automation and relationship to users

###  [breaking_changes]
- Removed previous drop-tenancy cleanup migration to restore official schema
## 0001-multi-tenancy-v1.1.0-major
### [update]
- Tenant panel now only exposes a minimal home page (no resource discovery)
- Added TenantHome page and view as tenant landing
- Seeders updated: added TenantSeeder and linked customer to tenant

### [new_features/whats-new]
- TenantHome page with current tenant display

###  [breaking_changes]
- Tenants cannot access admin resources (Customers, Posts, Services, Service Plans, User Subscriptions)

## 0001-multi-tenancy-v1.1.0-major
### [update]
- Add Filament User panel at /user with web guard + registration
- Set User panel home to SubscriberHome; create SubscriberHome page + view
- Add public auth redirects: /login and /register -> User panel auth routes
- Update User::canAccessPanel to allow 'user' for customer
- Remove Tenant panel and TenantHome (schema/model remain intact)

### [new_features/whats-new]
- Subscriber dashboard with contextual CTA: Create Tenant (if none) or My Tenant (if exists)

###  [breaking_changes]
- Tenant UI removed temporarily; tenant routes will be reintroduced later


## 0002-user-subscriber-foundation-v1.1.0-major
### [update]
- Add User panel placeholder pages: Pricing, Billing (Invoices/Payment Methods), Apps Settings, Support Tickets, Tenant Overview
- Register new pages in UserPanelProvider and set home to SubscriberHome
- Fix Filament v4 types: navigationGroup typed as \\UnitEnum|string|null across pages
- Add blade views for each page with empty states

### [new_features/whats-new]
- User panel navigation skeleton ready for business modules

###  [breaking_changes]
- None


## 0004-pos-foundation-v1.3.0-major
- Add POS-Schema-Plan.md (tables, migrations, factories outline)
- Define POS per-tenant, add-on lifetime, invoices & payments structure

