#!/usr/bin/env bash
set -e

: "${PORT:=10000}"

echo "Starting Apache on port $PORT..."

# Apache listens on Render's PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Ensure Laravel storage perms (ignore if folders missing)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Laravel warmup (safe)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec apache2-foreground
