# SUB DOMAIN & TENANCY
## Definisi
### Tenant
adalah organisasi/bisnis milik 'customer'. customer dapat memiliki banyak tenant.

### sub domain
adalah alamat sub domain. yang akan menjadi alamat tenant. sub domain ini mungkin dibuat otomatis atau manual. tergantung perkembangan development selanjutnya.

ada beberapa opsi pendekatan:
1. setelah tenant create, system akan membuat sub-domain baru sehingga nanti ketika langganan service akan langsung di deploy di sub-domain tersebut;
2. sub domain tidak di create saat tenant dibuat, melainkan saat langganan service dilakukan
3. sub-domain di create manual

## Keputusan (Default & Fallback)
- Default: provisioning subdomain dilakukan saat subscription/aktivasi layanan (opsi 2). Lebih efisien biaya/operasional, dan sejalan dengan event berpendapatan.
- Fallback: provisioning manual (opsi 3) bila otomatisasi gagal (tombol Retry/Provision di panel).
- Opsional: provisioning instan saat create tenant (opsi 1) khusus trial/POC.

## Pola URL & Boundary Tenancy
- Canonical URL tenant: `[tenant].mydomain.com`.
- Aplikasi/layanan tenant berada pada path, contoh: `/simrs`, `/pos` (memudahkan SSO/cookie lintas aplikasi).
- Custom domain per tenant didukung nantinya (CNAME), tetapi bukan bagian default awal.

## Arsitektur Tingkat Tinggi
- Satu aplikasi (single codebase) melayani multi-tenant; tidak ada deploy/instalasi aplikasi per tenant.
- Isolasi data dilakukan pada layer database:
  - MySQL: database per tenant (mis. `tenant_<id>`).
  - Postgres: schema per tenant (mis. `tenant_<id>`).
- Resolver tenant membaca Host header → set “current tenant” → switch koneksi DB `tenant` untuk query/model tenant-aware.

## Status & Siklus Provisioning Domain
- Status: `Reserved → Provisioning → Active → Failed`.
- Metadata: `provisioned_at`, `provision_error` (jika gagal), `subdomain` (unik).

## Alur Provisioning 1‑Klik (di background)
1) Customer klik “Aktifkan/Subscribe” layanan untuk tenant.
2) Sistem enqueue job chain Provisioning:
   - Reserve & validasi `subdomain` (format + global unik).
   - Cloudflare API: buat DNS CNAME `[tenant] → app.mydomain.com` (proxied, idempoten, retry backoff).
   - Buat database/schema tenant via koneksi “provisioner”, jalankan migrasi + seed pada koneksi `tenant`.
   - Tandai status `Active` dan set `provisioned_at`; jika terjadi kegagalan → `Failed` dan simpan `provision_error`.
3) UI menampilkan progres (Provisioning/Active/Failed) dan menyediakan tombol Retry.

## SSL / Sertifikat
- Gunakan wildcard `*.mydomain.com` di origin atau Cloudflare Universal SSL (proxy), sehingga tidak perlu menerbitkan sertifikat per tenant.
- Mode yang direkomendasikan: Full (strict) bila memungkinkan.

## Runtime Tenancy
- Middleware/Service Resolver: baca host → ambil tenant (cache) → set konteks tenant.
- Switch koneksi: set connection `tenant` (MySQL: nama database per tenant; Postgres: `search_path` schema tenant).
- Model tenant-aware: turunan base model yang mengarah ke connection `tenant`.
- Cache prefix (opsional): `tenant_<id>` untuk mencegah kebocoran cache antar tenant.
- Job middleware: rehydrate konteks tenant sebelum eksekusi job.

## Keamanan & Akses
- Peran global: `system/admin` (root). Peran tenant: owner/member (Spatie Permission teams; team=tenant).
- Prinsip least‑privilege untuk koneksi “provisioner” DB (izin CREATE saja yang diperlukan).
- Rate limit & idempotency pada proses provisioning.

## Operasional & Observability
- Logging/metrics diberi label tenant (subdomain/id) untuk traceability.
- Backup/restore per tenant (DB per tenant) atau per DB (schema per tenant) dengan filter schema.
- Migrasi skema per tenant dilakukan batch via job; harus idempoten dan dapat dilanjutkan.

## Checklist Implementasi (Tahap Awal)
- Model/Tabel `tenants`:
  - Tambah kolom: `subdomain` (unik), `domain_status` (enum), `provisioned_at`, `provision_error`.
- Validasi form CreateTenant: `subdomain` (regex format subdomain + unik global).
- Jobs:
  - `ProvisionTenantDomain` (Cloudflare DNS): idempoten + retry.
  - `ProvisionTenantDatabase` (DB/schema + migrasi/seed).
  - Orkestrasi job chain + UI “Retry”.
- Runtime:
  - Tenant resolver + switching koneksi `tenant`.
  - Base `TenantModel` + cache prefix per tenant.
- Filament/Panel:
  - Aksi Provision/Retry pada halaman tenant dengan tampilan status & error.

## Catatan Lanjutan (Non-Default)
- Custom domain per tenant akan membutuhkan verifikasi kepemilikan (CNAME/TXT) dan sertifikat per domain (atau Cloudflare SSL for SaaS).
- Pemisahan backend per aplikasi (SIMRS, POS, dll.) dapat dilakukan via path routing atau proxy layer‑7 jika diperlukan.
