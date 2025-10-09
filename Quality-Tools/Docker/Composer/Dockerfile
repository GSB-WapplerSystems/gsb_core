FROM composer:2.8@sha256:80af5a9f781dc23f83ed8fcd9d152b8498f720ccb237f3355b32071b1399ddd4 AS composer
FROM php:8.3.26-apache@sha256:40d0493cd5d87138f942ea4c38df4179f7f6b0852dfdf6724cbb0b906e6eed2f

ARG username
ARG uid
ARG gid

# Get Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN (groupadd --gid $gid "$username" || groupadd "$username" || true) && (useradd  -l -m -s "/bin/bash" --gid "$username" --comment '' --uid $uid "$username" || useradd  -l -m -s "/bin/bash" --gid "$username" --comment '' "$username" || useradd  -l -m -s "/bin/bash" --gid "$gid" --comment '' "$username" || useradd -l -m -s "/bin/bash" --comment '' $username )
RUN usermod -aG sudo $username

# Install TYPO3
# hadolint ignore=DL3008,DL3009
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        git \
        libxml2-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpq-dev \
        libpng-dev \
        libz-dev \
        libzip-dev \
        zlib1g-dev \
        pkg-config \
        graphicsmagick

# Configure extensions
# hadolint ignore=SC2046
RUN docker-php-ext-configure gd --with-libdir=/usr/include/ --with-jpeg --with-freetype && \
    docker-php-ext-install -j$(nproc) intl mysqli soap gd zip opcache pgsql pdo_pgsql exif && \
    pecl install redis && \
    pecl install xdebug-3.4.1 && \
    docker-php-ext-enable redis xdebug

RUN apt-get clean && \
    apt-get -y purge \
        libxml2-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpq-dev \
        libpng-dev \
        libz-dev \
        libzip-dev \
        zlib1g-dev \
        pkg-config && \
    rm -rf /var/lib/apt/lists/* /usr/src/*

COPY php_conf/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html
