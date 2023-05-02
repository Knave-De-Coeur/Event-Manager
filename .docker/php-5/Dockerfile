FROM php:5.6-fpm-alpine

RUN apk --update add --no-cache \
        php5 \
        php5-bcmath \
        php5-dom \
        php5-ctype \
        php5-curl \
        php5-fpm \
        php5-gd \
        php5-iconv \
        php5-intl \
        php5-json \
        php5-mcrypt \
        php5-opcache \
        php5-openssl \
        php5-pdo \
        php5-pdo_mysql \
        php5-pdo_pgsql \
        php5-pdo_sqlite \
        php5-phar \
        php5-posix \
        php5-soap \
        php5-xml

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug-2.5.0 \
    && docker-php-ext-enable xdebug


COPY --from=composer:2.2.0 /usr/bin/composer /usr/local/bin/composer

COPY ./composer.json ./
RUN composer install --prefer-dist --no-dev --no-progress --no-interaction

COPY ./ .

WORKDIR /var/www/html

RUN composer dump-autoload --optimize

#EXPOSE 9000
#
#CMD ["php-fpm", "-F"]
