# User-Subscriber
Halaman ini akan menjadi acuan dan panduan pengembangan modul pengguna (non root/app).

## Definisi
### Registrant
Yang dimaksud registrant adalah calon pengguna yang akan mendaftar di aplikasi.

### User
yang dimaksud User di sini adalah Registrant yang telah sukses login namun belum memiliki langganan apapun (user_type = 'customer')

### Subscriber
yang dimaksud Subscriber adalah User yang telah memiliki satu atau lebih langganan layanan (service subscription).

## Dashboard
- CTA Register tenant jika belum memiliki tenant. hidden jika sudah ada.

## Menu
### Tenant
- visible jika sudah memiliki, hidden sebaliknya
- Tenant sub menu:
    * Setting => kira-kira apa yang bisa kita setting
    * Profile
    * lainnya kemudian
