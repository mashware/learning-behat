FROM php:7.4-fpm-alpine as basic

ENV FETCH_PACKAGES \
        libpq \
        libzip \
        git \
        openssl \
        unzip \
        zlib-dev \
        oniguruma-dev

ENV BUILD_PACKAGES \
        alpine-sdk \
        autoconf \
        postgresql-dev

ENV PERMANENT_PACKAGES \
        bash \
        openssh \
        git \
        libzip-dev

ENV PHP_EXTENSIONS \
        pdo \
        pdo_pgsql \
        zip \
        bcmath \
        mbstring \
        sockets

ENV COMPOSE_HTTP_TIMEOUT=3600
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -ex \
    && apk update \
    && apk add --no-cache --virtual .fetch-deps $FETCH_PACKAGES \
    && apk add --no-cache --virtual .build-deps $BUILD_PACKAGES \
    && apk add --no-cache $PERMANENT_PACKAGES \
    && docker-php-ext-install $PHP_EXTENSIONS \
    && ln -snf /usr/share/zoneinfo/Europe/Madrid /etc/localtime \
    && echo Europe/Madrid > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', Europe/Madrid > /usr/local/etc/php/conf.d/tzone.ini \
    && echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && mkdir /var/app

ENV PATH /var/app/bin:/var/app/vendor/bin:$PATH
WORKDIR /var/app

FROM basic as development
COPY docker/php/config/php_dev.ini /usr/local/etc/php/php.ini
RUN pecl channel-update pecl.php.net \
	&& pecl install xdebug-2.9.1 \
    && docker-php-ext-enable xdebug

ENV XDEBUG_IDE_KEY=PHPSTORM
ENV XDEBUG_REMOTE_ENABLE=1
ENV XDEBUG_REMOTE_PORT=9000
ENV XDEBUG_REMOTE_HOST=172.17.0.1

FROM development as test
ENV APP_ENV=prod
COPY bin/composer ./bin/composer
COPY ./composer.json ./composer.lock ./
RUN set -eux; \
    composer install --prefer-dist --no-scripts --no-progress --no-suggest; \
    composer clear-cache
COPY ./ /var/app

FROM basic as result
ENV APP_ENV=prod
COPY ./ /var/app
RUN composer install --no-dev --optimize-autoloader \
    && rm -rf \
        .git \
        .gitignore \
        docker \
        docs \
        docker-compose.yml \
        phpcs.xml.dist \
        phpunit.xml.dist \
        Makefile \
        tests

FROM result as production
COPY docker/php/config/php_prod.ini /usr/local/etc/php/php.ini
COPY --from=result /var/app ./
RUN chown -R www-data:www-data /var/www/
USER www-data