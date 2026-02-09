# Kelompok 1 – Layanan Autentikasi & Profil Pengguna (Identity Service)

Proyek ini adalah implementasi layanan Identity Service yang bertanggung jawab untuk menangani autentikasi, manajemen pengguna, dan penyediaan token JWT yang akan digunakan oleh layanan lain (Kelompok 2–5).

## Deskripsi Singkat

Identity Service menyediakan backend untuk:
- Registrasi pengguna (Dummy untuk keperluan pengembangan).
- Login dan autentikasi.
- Manajemen data pengguna (Mahasiswa, Dosen, Admin).
- Pembuatan JWT Token yang aman.
- Endpoint API untuk integrasi dengan layanan lain.
- Dokumentasi API menggunakan Swagger.

Selain itu, proyek ini juga mencakup aplikasi mobile sederhana berbasis Flutter untuk memfasilitasi login dan melihat profil pengguna.

## Fitur Utama

### Backend (Laravel)
1.  **Autentikasi & Otorisasi**:
    - Login menggunakan email/password.
    - Menghasilkan **JWT Token** sebagai mekanisme autentikasi antar layanan.
    - Role-based access control (Mahasiswa, Dosen, Admin).

2.  **Manajemen User**:
    - **Registrasi**: Endpoint dummy untuk mendaftarkan user baru.
    - **Profil Pengguna**: Endpoint untuk melihat profil diri sendiri (`GET /me`).
    - **Detail User**: Endpoint untuk melihat detail user lain berdasarkan ID (`GET /users/{id}`).
    - **List User**: Endpoint untuk melihat daftar user dengan filter role (`GET /users?role=...`).

3.  **Dokumentasi API**:
    - Terintegrasi dengan **Swagger UI** untuk memudahkan pengujian dan penggunaan oleh kelompok lain.

### Mobile App (Flutter)
- Fitur Login.
- Halaman untuk melihat profil pengguna setelah login.

## Teknologi yang Digunakan

- **Framework Backend**: [Laravel 12](https://laravel.com)
- **Bahasa Pemrograman**: PHP ^8.2
- **Database**: SQLite (Default) / MySQL
- **Autentikasi**: [JWT Auth](https://github.com/php-open-source-saver/jwt-auth)
- **Dokumentasi API**: [L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)

## Prasyarat Instalasi

Pastikan Anda telah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM (Opsional, jika ingin menjalankan frontend bawaan Laravel)

## Cara Instalasi & Menjalankan Project

Ikuti langkah-langkah berikut untuk menjalankan project di lokal:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/divaarestiinc/identity-service.git
    cd identity-service
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Sesuaikan konfigurasi database di file `.env` jika tidak menggunakan SQLite.

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Generate JWT Secret**
    Penting untuk menghasilkan secret key agar JWT berfungsi:
    ```bash
    php artisan jwt:secret
    ```

6.  **Migrasi Database & Seeding**
    Jalankan migrasi untuk membuat tabel dan seeder untuk data awal (user dummy):
    ```bash
    php artisan migrate --seed
    ```

7.  **Generate Dokumentasi Swagger**
    ```bash
    php artisan l5-swagger:generate
    ```

8.  **Jalankan Server**
    ```bash
    php artisan serve
    ```
    Project akan berjalan di `http://localhost:8000`.

## Dokumentasi API

Setelah server berjalan, dokumentasi lengkap API dapat diakses melalui browser di:

**[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)**

Gunakan halaman ini untuk melihat daftar endpoint, format request, dan response, serta mencoba API secara langsung.

## Daftar Endpoint Utama

| Method | Endpoint | Deskripsi | Auth |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/auth/register` | Mendaftarkan pengguna baru (Dummy) | No |
| `POST` | `/api/auth/login` | Login user untuk mendapatkan Token | No |
| `POST` | `/api/auth/logout` | Logout user (Invalidate Token) | Yes |
| `POST` | `/api/auth/refresh` | Refresh Token | Yes |
| `GET` | `/api/auth/me` | Mendapatkan data user yang sedang login | Yes |
| `GET` | `/api/users/{id}` | Mendapatkan detail user berdasarkan ID | Yes |
| `GET` | `/api/users` | Mendapatkan list user (bisa filter ?role=...) | Yes |

---
**Catatan**: Token yang dihasilkan dari endpoint `/login` harus disertakan pada header `Authorization: Bearer <token>` untuk mengakses endpoint yang membutuhkan autentikasi.
