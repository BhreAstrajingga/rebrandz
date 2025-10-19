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

## Side Menu
### Pricing
### Billing
- Invoice (LIST)
- Payment Method (CRUD)

### Apps
* Business Profile
* sub menu ini kelak akan berisi inti aplikasi bisnisnya. tergantung akan menjadi aplikasi apa nanti, bisa POS, QRCode Service, PDF Service, Sistem Informasi Rumah Sakit, dan lain lain.
* Setting => kira-kira apa yang bisa kita setting

### Tenant
- visible jika sudah memiliki (makna: diaktifkan), hidden sebaliknya
- Tenant sub menu:
    * sub menu ini kelak akan berisi inti aplikasi bisnisnya. tergantung akan menjadi aplikasi apa nanti, bisa POS, QRCode Service, PDF Service, Sistem Informasi Rumah Sakit, dan lain lain

### Support
- Mailing-Tickets
- FAQ
- How To
- Report Issue (?)
- About Service/Apps
- Eula (?)
- etc (?)

## User Menu
- User Profile
