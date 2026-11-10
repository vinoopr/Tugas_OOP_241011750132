# Login dan dashboard admin

Laravel UI 4.x menangani login melalui trait AuthenticatesUsers di
app/Http/Controllers/Auth/LoginController.php, termasuk pembatasan percobaan login.
Aset Bootstrap lokal yang sudah tersedia tetap digunakan tanpa membutuhkan server Vite.

## Membuat akun

Edit nama, email, dan password di database/seeders/AdminSeeder.php,
pada komentar DATA LOGIN ADMIN. Nilai awal untuk tugas lokal:
email `admin@example.com` dan password `GantiPassword123!`.

Jalankan dari direktori project:

```sh
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

Password disimpan sebagai hash. Menjalankan ulang seeder dengan email yang sama
memperbarui nama, password, dan penanda admin tanpa membuat duplikat.
Mengganti email di seeder membuat akun baru; akun dengan email lama tetap ada.
Seeder juga terdaftar pada DatabaseSeeder; perintah db:seed menjalankannya
bersama ProjectSeeder. Gunakan --class=AdminSeeder untuk membuat admin saja.
Registrasi publik dimatikan. Perintah admin:create diganti dengan seeder.
Buka /login, lalu login menggunakan akun yang dibuat.
Email tersebut otomatis muncul pada dropdown Administrator di kanan atas.
Logout menghapus sesi dan mengarahkan kembali ke /login.

## Lokasi kode dan logo

- Logo: public/images/branding/myprofile-logo.png
- Layout header, dropdown akun, sidebar, dan footer: resources/views/layouts/admin.blade.php
- Halaman dashboard: resources/views/admin/dashboard.blade.php
- Halaman login: resources/views/auth/login.blade.php
- Tema warna dan ukuran logo: public/css/admin.css (variabel --admin-red)
- Proteksi route: routes/web.php dan app/Http/Middleware/EnsureAdmin.php
- Data akun admin: database/seeders/AdminSeeder.php

Komentar LOKASI LOGO di view menandai tempat pemanggilan gambar.
Logo MyProfile memadukan huruf P (profil/portofolio) dan simbol kode (project web).
Logo merah ditampilkan putih di header merah memakai filter CSS; file aslinya tetap merah.
Desain logo dibuat menggunakan imagegen bawaan dengan prompt: logo horizontal MyProfile,
monogram P dan simbol kode, merah marun #B91C1C, latar transparan, untuk portofolio mahasiswa.

Middleware auth dan EnsureAdmin melindungi seluruh /admin beserta CRUD.
Login hanya menerima akun dengan is_admin = true; akun user biasa ditolak.
Halaman /, /about, /profile, /project dan detail project tetap publik.

## Verifikasi

```sh
php artisan test
```
