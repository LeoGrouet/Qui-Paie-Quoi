FROM composer:2.7.6 AS composer

#####################################

FROM php:8.3-fpm-alpine3.17 AS base

WORKDIR /usr/src/app

ARG UID=1000
ARG GID=1000

RUN mkdir -p /usr/src/app && chown -R $UID:$GID /usr/src/app

RUN addgroup -g $GID user \
    && adduser -D -u $UID -G user user \
    && chown -R user:user /usr/src/app

RUN apk add --no-cache \
    autoconf \
    bash \
    git \
    libzip-dev \
    icu-dev \
    rabbitmq-c-dev \
    postgresql-dev

RUN apk add --no-cache --virtual .phpize-deps ${PHPIZE_DEPS}

RUN pecl install apcu amqp && docker-php-ext-enable apcu amqp

RUN docker-php-ext-configure zip && docker-php-ext-install -j"$(nproc)" \
    zip \
    intl \
    opcache \
    pdo \
    pdo_pgsql

RUN apk del --no-network .phpize-deps

COPY php/php.ini /usr/local/etc/php/php.ini

COPY --chown=$UID:$GID . /usr/src/app

ENV PATH="$PATH:/usr/src/app/bin"

USER user

FROM base AS dev

ENV APP_ENV=dev
ENV APP_DEBUG=1

COPY --from=composer   /usr/bin/composer /usr/local/bin/composer

USER 0

RUN apk add --no-cache gcc g++ make autoconf linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del --no-network gcc g++ make autoconf linux-headers

USER user

FROM base AS prod

ENV APP_ENV=prod
ENV APP_DEBUG=0

COPY . .

COPY php/php.prod.ini /usr/local/etc/php/php.ini

COPY --from=composer   /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-scripts --no-dev
