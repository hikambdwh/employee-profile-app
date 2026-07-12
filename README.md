# Employee Profile App

A Laravel 10.x project untuk manajemen profil karyawan, termasuk impor data karyawan menggunakan `maatwebsite/excel`.

## Ringkasan Proyek

- Framework: Laravel 10.x
- PHP: ^8.1
- Database: any database yang didukung Laravel (MySQL, PostgreSQL, SQLite, SQL Server)
- Frontend: Vite + Tailwind CSS + Flowbite
- Fitur utama: CRUD data karyawan, import Excel, dashboard responsif

## Persyaratan

- PHP 8.1 atau lebih tinggi
- Composer
- Node.js dan npm
- Database yang sudah dikonfigurasi pada `.env`

## Instalasi

1. Clone repositori ini

   ```bash
   git clone <repository-url>
   cd employee-profile-app
   ```

2. Install dependensi PHP

   ```bash
   composer install
   ```

3. Install dependensi JavaScript

   ```bash
   npm install
   ```

4. Salin file lingkungan dan buat key aplikasi

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

5. Atur konfigurasi database di `.env`

   Contoh koneksi MySQL:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=nama_user
   DB_PASSWORD=password
   ```

6. Jalankan migrasi dan seeder bila diperlukan

   ```bash
   php artisan migrate
   ```

   Atau jika ingin membersihkan dan membuat ulang semua tabel:

   ```bash
   php artisan migrate:fresh
   ```

## Menjalankan Aplikasi

- Jalankan server PHP bawaan Laravel:

  ```bash
  php artisan serve
  ```

- Jalankan Vite untuk mode development:

  ```bash
  npm run dev
  ```

- Untuk build produksi:

  ```bash
  npm run build
  ```

Akses aplikasi di `http://127.0.0.1:8000` setelah server berjalan.

## Dependensi Utama

### PHP / Composer

- `php`: ^8.1
- `laravel/framework`: ^10.10
- `guzzlehttp/guzzle`: ^7.2
- `laravel/sanctum`: ^3.3
- `laravel/tinker`: ^2.8
- `maatwebsite/excel`: ^3.1

### Dev Dependencies PHP

- `phpunit/phpunit`: ^10.1
- `nunomaduro/collision`: ^7.0
- `spatie/laravel-ignition`: ^2.0
- `laravel/pint`: ^1.0
- `laravel/sail`: ^1.18
- `fakerphp/faker`: ^1.9.1
- `mockery/mockery`: ^1.4.4

### JavaScript / npm

- `vite`: ^5.0.0
- `laravel-vite-plugin`: ^1.0.0
- `axios`: ^1.6.4
- `tailwindcss`: ^4.3.2
- `@tailwindcss/vite`: ^4.3.2
- `flowbite`: ^4.0.2
- `flowbite-typography`: ^1.0.5

## Struktur Penting

- `app/` - source code aplikasi Laravel
- `routes/web.php` - routing web utama
- `resources/views/` - blade templates, termasuk komponen dashboard
- `resources/css/` dan `resources/js/` - asset frontend
- `database/migrations/` - migrasi tabel
- `database/seeders/` - seeder data awal

## Testing

Menjalankan PHPUnit:

```bash
php artisan test
```
