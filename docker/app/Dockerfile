FROM node:18-alpine3.18 as node
FROM composer:latest as composer
FROM php:8.3-fpm-alpine3.18

ARG TZ
ARG UID
ARG GID

# Установка timezone
RUN set -eux \
    && apk add --no-cache tzdata \
    && cp /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo "${TZ}" > /etc/timezone \
    && apk del tzdata

# Установка пакетов
RUN set -eux \
    && apk add --no-cache \ 
        shadow \
        openssh \
        openssl \
        bash \
        zsh \
        zsh-vcs \
        linux-headers \
        freetype \
        icu \
        icu-libs \
        libzip \
        libpng \
        libjpeg-turbo \
        imagemagick \
        build-base \
        procps \
        make \
        nano \
        vim \
        git \
        wget \
        libwebp \
        libwebp-tools \
        lz4 \
        mysql-client

# Установка php пакетов
RUN set -eux \
    && apk add --no-cache --virtual .build-deps-php \
        $PHPIZE_DEPS \
        openssl-dev \
        zlib-dev \
        imagemagick-dev \
        icu-dev \
        g++ \
        automake \
        autoconf \
        libzip-dev \
        libpng-dev \
        libwebp-dev \
        libjpeg-turbo-dev \
        freetype-dev \
    && pecl install \
        redis \
        xdebug \
        mongodb \
        # protobuf \
        # grpc \
        # imagick \
    && docker-php-ext-configure \
        gd --enable-gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo \
        pdo_mysql \
        intl \
        pcntl \
        opcache \
        exif \
        zip \
    && docker-php-ext-enable \
        redis \
        xdebug \
        mongodb \
        # protobuf \
        # grpc \
        # imagick \
    && apk del -f .build-deps-php \
    && rm -rf /usr/src/php* \
    && rm -rf /tmp/pear

# Решение проблем с установкой imagick https://github.com/Imagick/imagick/issues/643
RUN set -eux \
    && apk add --no-cache --virtual .build-deps-imagick \
        $PHPIZE_DEPS \
        autoconf \
        imagemagick-dev \
    && curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/7088edc353f53c4bc644573a79cdcd67a726ae16.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && apk del -f .build-deps-imagick \
    && rm -rf /tmp/*

# Установка python3 и instaloader
ENV PYTHONUNBUFFERED=1
RUN set -eux \
    && apk add --no-cache python3 python3-dev py3-pip ffmpeg \
    && ln -sf python3 /usr/bin/python \
    && pip3 install --no-cache --upgrade pip setuptools virtualenv \
    && pip3 install instaloader \
    && pip3 install yt-dlp

# Установка nodejs, npm, yarn
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/include/node /usr/local/include/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /opt/yarn-v*/bin/* /usr/local/bin/
COPY --from=node /opt/yarn-v*/lib/* /usr/local/lib/
RUN ln -vs /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -vs /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

# Установка конфигурации
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Установка composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Настройка git
RUN git config --system --add safe.directory '*'

# Настройка пользователя
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data
USER www-data
RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
RUN git clone https://github.com/zsh-users/zsh-syntax-highlighting /home/www-data/.oh-my-zsh/custom/plugins/zsh-syntax-highlighting
RUN git clone https://github.com/zsh-users/zsh-autosuggestions /home/www-data/.oh-my-zsh/custom/plugins/zsh-autosuggestions

# Порты
EXPOSE 9000 8000 5173 8005

# Старт php-fpm
CMD ["php-fpm"]
