# Agent Guidelines for `saas_starterkit`

These principles are authoritative guidance for any agentic work in this repository. Their scope covers the entire directory tree.

## Core Principles

- Pertahankan gaya coding pattern yang telah diterapkan secara luas di repo ini.
- Hindari duplikasi kode; pertahankan logika terpusat agar mudah di-maintain.
- Pilih solusi yang sederhana; jangan overengineering atau menambah layer yang tidak perlu.
- Hanya build yang diperlukan; hindari fitur-fitur hipotesis yang tidak akan digunakan.
- Setiap class diusahakan hanya memiliki satu tanggung jawab; pertahankan fokus dan keterikatan komponen/elemen/entitas.
- Usahakan agar kode terbuka untuk pengembangan/ekstensi namun tertutup untuk modifikasi (Open/Closed Principle).
- Sub-class sebisa mungkin berguna untuk parent-nya tanpa merusak fungsi (Liskov Substitution Principle).
- Rancang antarmuka kecil dan fokus, bukan yang besar dan umum.
- Modul level tinggi jangan bergantung pada modul level rendah; keduanya harus bergantung pada abstraksi (Dependency Inversion).
- Gunakan bahasa Indonesia dalam percakapan.
- Coding pattern, gunakan bahasa english-us.

## Agent Application Notes

- Ikuti pola, struktur, dan penamaan yang sudah ada; perubahan harus minimal dan fokus pada tugas.
- Perbaiki akar masalah; jangan tambal permukaan kecuali diperlukan.
- Hindari memindah/rename file tanpa alasan kuat terkait tugas.
- Jangan menambah lapisan arsitektur baru kecuali terbukti perlu sesuai prinsip di atas.
- Sentralisasi logika bisnis di tempat yang sudah menjadi sumber kebenaran dalam codebase.
- Ketika menulis kode baru, prioritaskan komponen yang kecil, kohesif, dan mudah diuji.
- Jaga keterpisahan tanggung jawab di kelas, layanan, dan view; hindari kebocoran aturan domain ke lapisan presentasi.
- Pastikan perubahan bersifat extensible melalui strategi/abstraksi ringan, tidak melalui modifikasi berulang pada inti.

## Scope and Precedence

- Scope file ini: seluruh repo `saas_starterkit`.
- Instruksi langsung dari user atau sistem memiliki prioritas lebih tinggi daripada file ini.
- Jika ada AGENTS.md yang lebih dalam dengan instruksi berbeda, yang lebih dalam mengambil prioritas pada cakupannya.


## Tujuan

Membangun SaaS starterkit siap pakai dengan:
- Laravel 12 + Filament 4
- Multi-tenant (shared schema)
- Role & permission
- Billing modular
- Deployment sederhana (Docker optional)

## Arsitektur

- Layer utama
- Panel:
    - Admin Panel (panel provider)
    - Tenant Panel

## Fitur minimum

- Tenant registration: User daftar → record tenant + user
- Multi-tenant scope: GlobalScope `tenant_id` di model
- Authentication: Laravel Fortify
- Role & permission: Spatie Permission package
- Filament multi-panel: `AdminPanel` + `TenantPanel` terpisah
- Browser/Device Session (Jetstream)
- Subscription: modul billing dummy (Stripe / Midtrans stub, dan yang berlaku di Indonesia)
- Domain/Subdomain routing: wildcard `*.app.test`
- API token: Laravel Sanctum
- Logging & monitoring: Filament log viewer / Telescope optional, Laravel Pulse, Laravel Horizon, Laravel Boost (untuk development)
- Laravel Scout MySQL driver
- dan lainnya yang diperlukan untuk membangun SaaS starterkit

## MVP

- User bisa daftar → otomatis jadi tenant aktif
- Tenant login → akses Filament tenant panel
- Data terisolasi antar tenant
- Admin bisa suspend / delete tenant
- Browser/device session; user bisa logout session di perangkat lain

- Secure login & registration: bot-proof / human-only (rate limiting, captcha-ready)
