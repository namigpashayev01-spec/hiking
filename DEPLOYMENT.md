# Production Yerləşdirmə Təlimatı

Bu sənəd HikingAz layihəsini canlı serverə (Apache + MySQL + PHP-FPM və ya XAMPP / shared hosting) yerləşdirmək üçün addım-addım göstərişləri verir.

## 1. Server tələbləri

- PHP **8.2** və ya yuxarı
  - `ext-pdo_mysql`, `ext-mbstring`, `ext-openssl`, `ext-tokenizer`, `ext-xml`, `ext-ctype`, `ext-json`, `ext-bcmath`, `ext-fileinfo`, `ext-gd` (şəkil yükləmə üçün)
- MySQL **5.7+** və ya MariaDB **10.3+**
- Composer 2
- Apache (mod_rewrite aktiv) və ya Nginx
- Free disk: ən azı 500 MB

## 2. Kodun serverə yüklənməsi

```bash
git clone https://github.com/namigpashayev01-spec/hiking.git
cd hiking
composer install --no-dev --optimize-autoloader
```

## 3. Mühit (`.env`) konfiqurasiyası

```bash
cp .env.example .env
php artisan key:generate --force
```

`.env`-də aşağıdakı dəyərləri redaktə edin:

```env
APP_NAME=HikingAz
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.az

APP_LOCALE=az
APP_FALLBACK_LOCALE=en

LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hiking_search
DB_USERNAME=hiking_user
DB_PASSWORD=GÜCLÜ-ŞİFRƏ
```

**Mütləq:** `APP_DEBUG=false` olmalıdır — əks halda istifadəçilərə daxili stack trace görünə bilər.

## 4. Verilənlər bazası

MySQL-də yeni baza və istifadəçi yaradın:

```sql
CREATE DATABASE hiking_search CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hiking_user'@'localhost' IDENTIFIED BY 'GÜCLÜ-ŞİFRƏ';
GRANT ALL PRIVILEGES ON hiking_search.* TO 'hiking_user'@'localhost';
FLUSH PRIVILEGES;
```

Miqrasiya və ilkin seed:

```bash
php artisan migrate --force
php artisan db:seed --force
```

> **Vacib:** Production-a yerləşdirdikdən sonra `DatabaseSeeder.php`-dakı default şifrələri (`password`) və emailləri dərhal dəyişdirin. Admin panelindən birbaşa istifadəçinin şifrəsini yeniləmək imkanı yoxdursa, `php artisan tinker` ilə yenidən təyin edin:
>
> ```php
> $u = App\Models\User::where('email','admin@hiking.az')->first();
> $u->password = 'yeni-güclü-şifrə';
> $u->save();
> ```

## 5. İcazələr

Web server istifadəçisi (`www-data` və ya `apache`) aşağıdakı qovluqlara yazma icazəsi almalıdır:

```bash
chown -R www-data:www-data storage bootstrap/cache public/uploads
chmod -R 775 storage bootstrap/cache public/uploads
```

Windows / XAMPP-də əlavə icazə tənzimi adətən tələb olunmur.

## 6. Apache konfiqurasiyası

`DocumentRoot` layihənin **`public/`** qovluğuna baxmalıdır:

```apache
<VirtualHost *:80>
    ServerName your-domain.az
    ServerAlias www.your-domain.az
    DocumentRoot /var/www/hiking/public

    <Directory /var/www/hiking/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/hiking-error.log
    CustomLog ${APACHE_LOG_DIR}/hiking-access.log combined
</VirtualHost>
```

`mod_rewrite` aktiv olmalıdır:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### XAMPP (Windows) üzərində

XAMPP virtual host konfiqurasiyası `xampp/apache/conf/extra/httpd-vhosts.conf` faylındadır. Yuxarıdakı blokun ekvivalentini əlavə edin və `C:/xampp/apache/bin/httpd.exe -k restart` ilə yenidən başladın.

## 7. Nginx konfiqurasiyası (alternativ)

```nginx
server {
    listen 80;
    server_name your-domain.az;
    root /var/www/hiking/public;

    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 8. Cache optimallaşdırması

Hər deploydan sonra:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Köhnə cache-i təmizləmək lazım gələrsə:

```bash
php artisan optimize:clear
```

## 9. HTTPS (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d your-domain.az -d www.your-domain.az
```

`.env`-də `APP_URL=https://...` təyin edildiyindən əmin olun.

## 10. Şəkil yükləmələri

- Tur şəkilləri `public/uploads/tours/`-a yazılır.
- Maksimum şəkil ölçüsü: **4 MB** (`TourController::validateTour`-dan).
- Yedəkləmədə bu qovluğu da daxil edin — repo-da yalnız `.gitkeep` saxlanılır.

## 11. Yedəkləmə (backup)

Müntəzəm olaraq yedəklənməlidir:

1. **Verilənlər bazası:**
   ```bash
   mysqldump -u hiking_user -p hiking_search > backup-$(date +%F).sql
   ```
2. **Yüklənmiş şəkillər:** `public/uploads/tours/` qovluğu.
3. **`.env` faylı** (təhlükəsiz yerdə).

## 12. Monitorinq və log

- Laravel logları: `storage/logs/laravel.log`
- Apache: `/var/log/apache2/hiking-error.log`
- PHP-FPM: `/var/log/php8.2-fpm.log`

`LOG_LEVEL=warning` production üçün məsləhətdir (debug log-ları yığmamaq üçün).

## 13. Yeniləmə (update) prosedurası

```bash
cd /var/www/hiking
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up
```

## 14. Təhlükəsizlik yoxlama siyahısı

- [ ] `.env`-də `APP_DEBUG=false`
- [ ] `.env`-də güclü `APP_KEY` (`php artisan key:generate` ilə)
- [ ] MySQL istifadəçisi güclü şifrə ilə (`root` deyil)
- [ ] Default seed şifrələri (`password`) dəyişdirilib
- [ ] HTTPS aktiv (SSL sertifikat)
- [ ] `public/` qovluğundan kənara birbaşa giriş bağlıdır (DocumentRoot düzgün təyin edilib)
- [ ] `storage/`, `bootstrap/cache/`, `public/uploads/` icazələri düzgündür
- [ ] Müntəzəm yedəkləmə qurulub
