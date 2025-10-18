# TENANCY
Filament Official definition vs custom

## Official Definition
Official source: https://filamentphp.com/docs/4.x/users/tenancy

## Custom Definition
1. Root, app provider
2. Subscriber:
    - User
        - Tenants: Tenants admin-users (staff)

### Root
adalah kita. yang akan mengelola aplikasi secara keseluruhan, tenant, subscription, layanan, support, ticketing dan lainnya dalam wilayah root.

### Subscriber
Subscriber adalah user yang mendaftar untuk berlangganan service/layanan yang disediakan. setiap subscriber bisa bertindak sebagai individu yang akan menggunakan layanan untuk dirinya sendiri secara langsung, maupun juga bisa sebagai tenant owner (membuat/memiliki Tenant). Subscriber ini bertindak sebagai superadmin di tanent miliknya jika ia memiliki tanent. Di dalam tanent tersebut, subscriber bisa menambahkan penggunanya sendiri. sehingga seperti ini: User Type Subscriber->hasOne Tenants->hasMany Users.

### Roles & Permissions (Tenant Scope)
- Roles dan permissions pada level tenant dimiliki dan diatur oleh Tenant Owner (Subscriber) dan hanya berlaku dalam tenant tersebut.
- Gunakan Spatie Permission dengan fitur `teams` diaktifkan dan mapping `teams` ke model `Tenant` (`tenant_id` sebagai foreign key) untuk mendukung role/permission per-tenant.
- Root roles (mis. RootAdmin, Support) bersifat global dan tidak boleh mempengaruhi scope tenant.
- Tenant panel harus menyediakan antarmuka untuk mengelola: roles per-tenant, daftar permissions, dan assignment role ke tenant users.
- Opsional: gunakan Filament Shield untuk scaffolding di atas Spatie, selama kompatibel dengan Filament v4.
1. Subscriber dapat membuat Role khusus sesuai kebutuhan masing masing subscriber untuk tenant user miliknya.
