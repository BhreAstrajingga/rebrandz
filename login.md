# Login Guidelines

Tujuan: memberikan panduan singkat dan konsisten untuk implementasi dan pengujian mekanisme login serta routing setelah autentikasi.

## Prinsip Utama

-   Hanya ada 1 halaman login: root/login (route: GET|POST /login).
-   Login harus memeriksa kredensial dan atribut user_type pada user.
-   Routing setelah login: - jika user_type === 'admin' atau 'system' → redirect ke /admin
-   jika user_type === 'customer' → redirect ke /user
-   Tidak boleh ada halaman login lain (mis. /admin/login, /admin/wakwaw, /user/login). Semua alur login melewati /login.

## Rute

-   Pastikan route login tunggal terdaftar dan diberi nama:
    -   Route::get('/login', ...)
    -   Route::post('/login', ...)
    -   Gunakan route('login') untuk referensi dan link.

## Autentikasi & Autorisasi

-   Validasi kredensial dilakukan seperti biasa.
-   Setelah kredensial valid, baca properti user_type pada model User untuk menentukan tujuan redirect.
-   Jangan mengubah user_type di proses redirect—hanya baca untuk routing.

## Layout, flow, logic, method

-   login page yang baru, harus memanfaatkan Login filament form begitu pula registrasi, forgot password dan fungsi-fungsi lain.
-   fungsi-fungsi register, forgot password dan lainnya harus tetap tersedia dan mengikuti filament ekosistem, layaknya di filament.
-   termasuk logic, validasi, rate limit dan lainnya tetap mengikuti filament.
