# HikingAz

Azərbaycanda hiking və təbiət turlarını elan etmək üçün Laravel 12 əsaslı platforma. Şirkətlər qeydiyyatdan keçir, admin təsdiqləyir, təsdiqlənmiş şirkətlər tur elanı əlavə edir, admin elanları yoxlayır, yalnız təsdiqlənmiş turlar ana səhifədə görünür.

## Texnologiyalar

- **Backend:** PHP 8.2+, Laravel 12
- **Verilənlər bazası:** MySQL 5.7+ (development üçün SQLite də işləyir)
- **Frontend:** Blade + custom CSS (Tailwind/Vite yoxdur)
- **Redaktor:** CKEditor 5 (CDN)
- **Test:** PHPUnit 11

## Xüsusiyyətlər

- İki rollu istifadəçi sistemi (admin / şirkət) — tək `users` cədvəlində `role` + `status` sütunları
- Şirkət qeydiyyatı və admin tərəfdən təsdiq / rədd
- Tur CRUD əməliyyatları (CKEditor ilə zəngin mətn)
- Admin moderasiya paneli (təsdiq / rədd səbəbi ilə)
- Çoxdilli interfeys (AZ / EN) — sessiya əsaslı
- Şəkil yükləmə (`public/uploads/tours/`)
- "Topoqrafik Ekspedisiya" temalı ana səhifə (Fraunces + Hanken Grotesk, custom palitra)

## Quraşdırma (lokal)

### Tələblər

- PHP 8.2+ (`pdo_mysql` və ya `pdo_sqlite` aktiv)
- Composer 2
- MySQL 5.7+ (və ya XAMPP)

### Addımlar

```bash
git clone https://github.com/namigpashayev01-spec/hiking.git
cd hiking
composer install
cp .env.example .env
php artisan key:generate
```

`.env` faylında verilənlər bazası ayarlarını dolduraraq:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=hiking_search
DB_USERNAME=root
DB_PASSWORD=
```

MySQL-də `hiking_search` adlı verilənlər bazası yaradın, sonra:

```bash
php artisan migrate --seed
php artisan serve
```

Sayt: <http://localhost:8000>

## Seed (demo) giriş məlumatları

| Rol | Email | Şifrə | Status |
|---|---|---|---|
| Admin | `admin@hiking.az` | `password` | Təsdiqlənmiş |
| Şirkət (təsdiqlənmiş, 3 turu var) | `company@hiking.az` | `password` | Təsdiqlənmiş |
| Şirkət (gözləyən) | `pending@hiking.az` | `password` | Gözləyir |

- Admin giriş səhifəsi: `/admin/login`
- Şirkət giriş səhifəsi: `/company/login`
- Şirkət qeydiyyatı: `/company/register`

## Arxitektura qeydləri

- **Tək `users` cədvəli** — şirkətlər üçün ayrıca `companies` cədvəli yoxdur. Fərq `role` (admin / company) və `status` (pending / approved / rejected) sütunları ilə qoyulur.
- **`tours` cədvəli** — `title`, `slug`, `description`, `content` (CKEditor HTML), `price`, `image`, `status`, `rejection_reason`.
- **Middleware:**
  - `role:admin` və `role:company` — `EnsureRole` (`app/Http/Middleware/EnsureRole.php`). Auth olmayan istifadəçini müvafiq login səhifəsinə yönləndirir.
  - `company.approved` — `EnsureCompanyApproved`. Gözləyən şirkətə tur əlavə etməyə icazə vermir (tur siyahısını göstərir).
  - `SetLocale` — sessiya əsasında `app()->setLocale()` çağırır.
- **Şəkillər:** `public/uploads/tours/`-a birbaşa `move()` ilə yazılır. Storage symlink istifadə edilmir (Windows uyumluluğu).
- **Tailwind / Vite YOXDUR** — bütün stillər `public/css/`-dədir: `app.css` (ümumi), `home.css` (ana səhifə teması).

## Test

```bash
php artisan test
```

Testlər in-memory SQLite-də işləyir (`pdo_sqlite` lazımdır).

## Production-a yerləşdirmə

Ətraflı yerləşdirmə təlimatı: [`DEPLOYMENT.md`](DEPLOYMENT.md).

Qısa addımlar:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force   # yalnız ilk dəfə
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link      # (uploads `public/uploads/`-dadır, lazım deyil)
```

`.env`-də `APP_ENV=production`, `APP_DEBUG=false`, real `APP_URL` mütləq dəyişdirilməlidir.

## Layihə strukturu

```
app/
  Http/
    Controllers/
      Admin/        # admin paneli (dashboard, turlar, şirkətlər)
      Auth/         # admin və şirkət auth
      Company/      # şirkət paneli (dashboard, turlar)
      HomeController, LocaleController
    Middleware/
      EnsureRole, EnsureCompanyApproved, SetLocale
  Models/
    User, Tour
config/             # standart Laravel konfiqurasiyaları
database/
  migrations/       # users, role/status, tours
  seeders/          # admin, demo şirkət, nümunə turlar
public/
  css/              # app.css, home.css (Tailwind yox — custom CSS)
  uploads/tours/    # tur şəkilləri
resources/
  views/            # blade şablonları
  lang/             # az.json (EN üçün açarların özü işləyir)
routes/
  web.php           # bütün marşrutlar
tests/
  Feature/TourFlowTest.php  # əsas axın testi
```

## Lisenziya

MIT
