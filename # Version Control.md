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
