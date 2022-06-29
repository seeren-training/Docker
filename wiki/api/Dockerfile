FROM php:8.1-fpm
RUN apt-get update \
    && apt-get install zip unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install pdo_mysql
WORKDIR /api
COPY . .
RUN composer install \
    && composer dumpautoload
CMD ["composer", "start"]
EXPOSE 8000