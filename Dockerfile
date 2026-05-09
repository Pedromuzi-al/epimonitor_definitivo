FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev libonig-dev libxml2-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000
CMD ["sh", "-lc", "php artisan migrate --force && (php artisan db:seed --class=DiseaseSeeder --force || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
