FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libsqlite3-dev \
    && docker-php-ext-install bcmath pdo pdo_mysql pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

RUN mkdir -p database bootstrap/cache storage/logs storage/framework/cache storage/framework/sessions storage/framework/views \
    && if [ ! -f .env ]; then cp .env.example .env; fi \
    && touch database/database.sqlite \
    && git config --global --add safe.directory /var/www/html \
    && composer install --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan key:generate --force \
    && chmod -R 775 bootstrap/cache storage database

EXPOSE 8000

CMD ["sh", "-c", "php artisan migrate --seed --force && php artisan l5-swagger:generate && php artisan serve --host=0.0.0.0 --port=8000"]
