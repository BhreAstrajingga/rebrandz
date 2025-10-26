# Rancangan Skema POS, Migrasi, dan Factory

## Tujuan
- POS per-tenant; cabang berbagi lisensi POS tenant yang sama.
- Add-on lifetime diikat ke pasangan tenant+service agar tidak hilang saat subscription berganti.
- Penagihan jelas: invoice + payment; paket monthly/yearly; add-on one-time.

## Tabel & Kolom (Detail)

### branches
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id` (cascade)
- `name` string(120)
- `code` string(60) unik per tenant
- `is_default` boolean default false
- `active` boolean default true
- `slug` string(120) unik per tenant
- `created_at`/`updated_at`

Indexes
- unique(`tenant_id`, `code`)
- unique(`tenant_id`, `slug`)
- index(`tenant_id`, `active`)

### warehouses (add-on)
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id` (cascade)
- `name` string(120)
- `code` string(60)
- `active` boolean default true
- `created_at`/`updated_at`

Indexes
- unique(`tenant_id`, `code`)
- index(`tenant_id`, `active`)

### services (existing)
- gunakan entri `POS` (type='pos') dan `slug` unik.

### service_plans (existing)
- tambah kolom jika perlu:
  - `price_monthly` decimal(12,2)
  - `price_yearly` decimal(12,2)
  - `features` json (opsional)

### user_subscriptions (existing, diubah)
- Tambah: `tenant_id` bigint, FK → `tenants.id` (cascade)
- Kolom lain tetap: `service_id`, `service_plan_id`, `subscription_code` (uuid), `start_date`, `end_date`, `status` (uppercase), `renewal_type`, `trial_ends_at`, `cancelled_at`

Constraints
- unique aktif: (partial index direkomendasikan) satu ACTIVE per (`tenant_id`, `service_id`)
- index(`tenant_id`, `service_id`, `status`)

### add_ons
- `id` bigint, PK
- `code` string(60) unik
- `name` string(120)
- `description` text nullable
- `price_one_time` decimal(12,2) default 0
- `status` string(20) default 'draft' (draft|active)
- `created_at`/`updated_at`

Indexes
- unique(`code`)
- index(`status`)

### tenant_service_add_ons (entitlement lifetime)
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `service_id` bigint, FK → `services.id`
- `add_on_id` bigint, FK → `add_ons.id`
- `activated_at` datetime
- `status` string(20) default 'active' (active|inactive)
- `created_at`/`updated_at`

Indexes
- unique(`tenant_id`, `service_id`, `add_on_id`)
- index(`tenant_id`, `service_id`, `status`)

### products
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `sku` string(60)
- `name` string(180)
- `price` decimal(12,2)
- `tax_rate` decimal(5,2) default 0
- `category_id` bigint nullable (opsional)
- `is_active` boolean default true
- `slug` string(180) unik per tenant
- `created_at`/`updated_at`

Indexes
- unique(`tenant_id`, `sku`)
- unique(`tenant_id`, `slug`)
- index(`tenant_id`, `is_active`)

### pos_sales
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `branch_id` bigint, FK → `branches.id`
- `customer_id` bigint nullable (opsional ke customers/users)
- `total` decimal(12,2)
- `tax_total` decimal(12,2) default 0
- `discount_total` decimal(12,2) default 0
- `status` string(20) default 'open' (open|paid|refunded)
- `payment_method` string(40) nullable
- `paid_at` datetime nullable
- `reference` string(120) nullable
- `created_at`/`updated_at`

Indexes
- index(`tenant_id`, `branch_id`, `status`)
- index(`paid_at`)

### pos_sale_items
- `id` bigint, PK
- `sale_id` bigint, FK → `pos_sales.id` (cascade)
- `product_id` bigint, FK → `products.id`
- `qty` unsignedInteger
- `unit_price` decimal(12,2)
- `tax_rate` decimal(5,2) default 0
- `discount` decimal(12,2) default 0
- `subtotal` decimal(12,2)
- `created_at`/`updated_at`

Indexes
- index(`sale_id`)
- index(`product_id`)

### stock_movements
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `branch_id` bigint FK → `branches.id`
- `warehouse_id` bigint nullable FK → `warehouses.id`
- `product_id` bigint FK → `products.id`
- `qty` integer (negatif untuk out)
- `type` string(12) (in|out|adjust|transfer)
- `reference` string(120) nullable
- `created_at`/`updated_at`

Indexes
- index(`tenant_id`, `branch_id`, `product_id`)
- index(`warehouse_id`)
- index(`type`)

### invoices
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `subscription_id` bigint nullable FK → `user_subscriptions.id`
- `number` string(40) unik per tenant
- `status` string(20) default 'pending' (pending|paid|void)
- `issued_at` datetime
- `due_at` datetime nullable
- `total` decimal(12,2) default 0
- `currency` string(3) default 'IDR'
- `created_at`/`updated_at`

Indexes
- unique(`tenant_id`, `number`)
- index(`tenant_id`, `status`)

### invoice_items
- `id` bigint, PK
- `invoice_id` bigint, FK → `invoices.id` (cascade)
- `type` string(20) (plan|addon)
- `description` string(180)
- `quantity` unsignedInteger default 1
- `unit_price` decimal(12,2)
- `subtotal` decimal(12,2)
- `created_at`/`updated_at`

Indexes
- index(`invoice_id`)
- index(`type`)

### payments
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `invoice_id` bigint, FK → `invoices.id` (cascade)
- `amount` decimal(12,2)
- `method` string(40)
- `provider` string(60) nullable
- `transaction_id` string(120) nullable
- `status` string(20) default 'pending' (pending|paid|failed)
- `paid_at` datetime nullable
- `note` text nullable
- `created_at`/`updated_at`

Indexes
- index(`tenant_id`, `status`)
- index(`invoice_id`)

### payment_methods
- `id` bigint, PK
- `tenant_id` bigint, FK → `tenants.id`
- `type` string(20) (card|bank|wallet)
- `brand` string(40) nullable
- `last4` string(4) nullable
- `is_default` boolean default false
- `meta` json nullable
- `created_at`/`updated_at`

Indexes
- index(`tenant_id`, `is_default`)

## Outline Migrasi

1) `create_branches_table`
- Buat tabel `branches` dengan FK ke `tenants` dan unique per-tenant untuk `code` dan `slug`.

2) `create_warehouses_table` (opsional)
- Buat tabel `warehouses` dengan FK ke `tenants`.

3) `alter_user_subscriptions_add_tenant`
- Tambah kolom `tenant_id` + index (`tenant_id`, `service_id`, `status`).
- Siapkan constraint unik untuk satu ACTIVE per (`tenant_id`, `service_id`) bila engine mendukung partial index; alternatif: validasi di model.

4) `create_add_ons_table`
- Buat tabel `add_ons`.

5) `create_tenant_service_add_ons_table`
- Buat tabel entitlement lifetime per `tenant_id + service_id + add_on_id`.

6) `create_products_table`
- Buat tabel `products` dengan unique per-tenant untuk `sku` dan `slug`.

7) `create_pos_sales_table` dan `create_pos_sale_items_table`
- Buat penjualan kasir dan itemnya.

8) `create_stock_movements_table`
- Catat semua pergerakan stok sebagai sumber kebenaran.

9) `create_invoices_table` dan `create_invoice_items_table`
- Buat struktur invoice dan itemnya.

10) `create_payments_table`
- Buat tabel pembayaran dan relasi ke invoice.

11) `create_payment_methods_table`
- Simpan metode pembayaran per-tenant.

12) Listener: `TenantCreated`
- Saat tenant dibuat, otomatis buat `branches` default (is_default=true).

## Outline Factory (PHPUnit)

- BranchFactory
  - `tenant_id` via related Tenant
  - `name`, `code`, `slug`, `is_default=false`, `active=true`

- WarehouseFactory (opsional)
  - `tenant_id`, `name`, `code`, `active=true`

- AddOnFactory
  - `code`, `name`, `description`, `price_one_time`, `status='active'`

- TenantServiceAddOnFactory
  - `tenant_id`, `service_id`, `add_on_id`, `activated_at`, `status='active'`

- ProductFactory
  - `tenant_id`, `sku`, `name`, `price`, `tax_rate`, `is_active=true`, `slug`

- PosSaleFactory
  - `tenant_id`, `branch_id`, `total`, `tax_total`, `discount_total`, `status='open'`

- PosSaleItemFactory
  - `sale_id`, `product_id`, `qty`, `unit_price`, `tax_rate`, `discount`, `subtotal`

- StockMovementFactory
  - `tenant_id`, `branch_id`, `product_id`, `qty`, `type`

- InvoiceFactory
  - `tenant_id`, `subscription_id?`, `number`, `status='pending'`, `issued_at`, `total`

- InvoiceItemFactory
  - `invoice_id`, `type='plan|addon'`, `description`, `quantity`, `unit_price`, `subtotal`

- PaymentFactory
  - `tenant_id`, `invoice_id`, `amount`, `method`, `status='paid'`, `paid_at`

- PaymentMethodFactory
  - `tenant_id`, `type`, `brand`, `last4`, `is_default`

## Catatan Implementasi
- Status di DB uppercase; UI Memetakan ke label/badge.
- Gunakan `casts()` di model (Laravel 12) untuk dates/arrays.
- Eager loading & index FK untuk performa list.
- Transaksi DB untuk operasi kritis (renew, pay, transfer stok).
- Resolver fitur: gabungkan fitur dari paket + add-on aktif untuk gating UI/policy.

## Langkah Berikutnya
- Review skema di atas; konfirmasi/ubah nama kolom sesuai preferensi bisnis.
- Setelah disetujui, scaffold migrasi dan factory bertahap (M1–M3), dilanjutkan implementasi UI POS dasar.
