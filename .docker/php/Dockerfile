FROM composer:latest AS composer

FROM php:fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    libpq-dev \
    python3 \
    python3-pip \
    wget \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    zip \
    libicu-dev \
  && rm -rf /var/lib/apt/lists/*

RUN pecl install redis \
  &&  rm -rf /tmp/pear

#
# PHP extensions
#
RUN docker-php-ext-configure \
    zip \
  && docker-php-ext-install \
    zip \
    pdo \
    pdo_pgsql \
    intl \
    opcache \
  ;

RUN docker-php-ext-enable \
    opcache \
    redis \
  ;

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /var/www/current
EXPOSE 9000

CMD ["php-fpm"]
