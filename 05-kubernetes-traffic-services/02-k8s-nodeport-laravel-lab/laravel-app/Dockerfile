# ============================================================
# Dockerfile - Laravel 12 Production Image
#
# Base image : php:8.3-apache
# Port       : 80
# Web server : Apache dengan mod_rewrite
#
# Build image:
#   docker build -t laravel-k8s-demo:v1 .
#
# Run container:
#   docker run -p 8080:80 laravel-k8s-demo:v1
# ============================================================

# ------ Stage: Production ------
FROM php:8.3-apache

# -----------------------------------------------
# 1. Install dependensi sistem dan ekstensi PHP
# -----------------------------------------------
RUN apt-get update && apt-get install -y \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
    # Install ekstensi PHP yang dibutuhkan Laravel
    && docker-php-ext-install zip \
    # Bersihkan cache apt untuk memperkecil ukuran image
    && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------
# 2. Aktifkan mod_rewrite Apache
#    Diperlukan agar .htaccess Laravel bisa bekerja
#    dan URL seperti /health /info bisa diakses
# -----------------------------------------------
RUN a2enmod rewrite

# -----------------------------------------------
# 3. Install Composer dari official image
#    Menggunakan multi-stage copy agar image lebih kecil
# -----------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -----------------------------------------------
# 4. Konfigurasi Apache Virtual Host
#    Mengarahkan DocumentRoot ke folder public/ Laravel
# -----------------------------------------------
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# -----------------------------------------------
# 5. Set working directory
# -----------------------------------------------
WORKDIR /var/www/html

# -----------------------------------------------
# 6. Copy source code ke dalam container
#    .dockerignore memastikan vendor/ dan .env
#    tidak ikut di-copy
# -----------------------------------------------
COPY . .

# -----------------------------------------------
# 7. Install PHP dependencies via Composer
#    --no-dev          : skip package development
#    --optimize-autoloader : optimasi class loading
#    --no-interaction  : non-interactive mode untuk CI/CD
# -----------------------------------------------
RUN composer install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction

# -----------------------------------------------
# 8. Setup environment file
#    Salin .env.example ke .env untuk default values
# -----------------------------------------------
RUN cp -n .env.example .env

# -----------------------------------------------
# 9. Generate application key
#    APP_KEY digunakan untuk enkripsi session dan cookie
# -----------------------------------------------
RUN php artisan key:generate --force

# -----------------------------------------------
# 10. Optimasi untuk production
#     Cache config, route, dan view
# -----------------------------------------------
RUN php artisan config:cache \
    && php artisan route:cache

# -----------------------------------------------
# 11. Set permissions yang benar
#     www-data adalah user Apache di container
# -----------------------------------------------
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# -----------------------------------------------
# 12. Expose port 80
#     Port ini di-map ke NodePort di Kubernetes
# -----------------------------------------------
EXPOSE 80

# -----------------------------------------------
# 13. Jalankan Apache di foreground via apache2ctl
#
#     Kenapa bukan "apache2-foreground"?
#     apache2-foreground (script PHP) terkadang tidak
#     me-load /etc/apache2/envvars dengan benar di PHP 8.3,
#     menyebabkan error "option requires an argument -- d".
#
#     apache2ctl adalah wrapper resmi Debian yang selalu
#     me-load envvars sebelum menjalankan Apache.
# -----------------------------------------------
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
