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

## Lisensi

Proyek ini menggunakan lisensi MIT.

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
