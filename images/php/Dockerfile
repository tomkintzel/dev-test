# Argumente
ARG PHP_VERSION=7.2

# @see https://hub.docker.com/_/php
FROM php:$PHP_VERSION-fpm-stretch
MAINTAINER mindsquare <info@mindsquare.de>

# Allgemein
RUN	apt-get update -y --fix-missing --no-install-recommends && \
	apt-get install -y \
		curl \
		libcurl3 \
		libcurl3-dev \
		zlib1g-dev && \
	docker-php-ext-install \
		curl \
		zip \
		json \
		mbstring \
		gettext

# Installiere XDebug
RUN	if echo "$PHP_VERSION" | grep -q "7.2" > /dev/null; then \
		pecl install \
			xdebug-2.9.2; \
	else \
		pecl install \
			xdebug-2.2.5; \
	fi && \
	docker-php-ext-enable \
		xdebug

# Installiere MySQL
# @see https://www.php.net/manual/de/intro.mysql.php
RUN	apt-get install -y \
		mysql-client && \
	docker-php-ext-install \
		pdo_mysql \
		mysqli

# Installiere OpCache
RUN	docker-php-ext-configure \
		opcache --enable-opcache && \
	docker-php-ext-install \
		opcache

# Installiere Module für die Bildbearbeitung
# @see https://www.php.net/manual/de/intro.image.php
RUN apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev && \
	if echo "$PHP_VERSION" | grep -q "7.2" > /dev/null; then \
		apt-get install -y \
			libwebp-dev && \
		docker-php-ext-configure \
			gd \
				--with-freetype-dir=/usr/include/ \
				--with-jpeg-dir=/usr/include/ \
				--with-webp-dir=/usr/include/; \
	else \
		apt-get install -y \
			libvpx-dev && \
		docker-php-ext-configure \
			gd \
				--with-freetype-dir=/usr/include/ \
				--with-jpeg-dir=/usr/include/ \
				--with-vpx-dir=/usr/include/; \
	fi && \
	docker-php-ext-install -j$(nproc) \
		gd

# Installiere Socket-Modul
# @see https://www.php.net/manual/en/intro.sockets.php
RUN	docker-php-ext-install sockets

# Installiere Metadaten von Bildern
RUN	docker-php-ext-install \
		exif && \
	docker-php-ext-enable \
		exif

# Installiere Memcached
# @see https://github.com/php-memcached-dev/php-memcached/#dependencies
RUN apt-get install -y \
		memcached \
		libmemcached-dev \
		libmemcached-tools && \
	pecl \
		update-channels && \
	if echo "$PHP_VERSION" | grep -q "7.2" > /dev/null; then \
		pecl install \
			memcached-3.1.5; \
	else \
		pecl install \
			memcached-2.2.0; \
	fi

# Clear
RUN	rm -rf /var/lib/apt/lists/*

# Installiere das CA-Zertifikat von der lokalen Entwickungsumgebung
COPY config/ssl/certs/cacert.crt /usr/local/share/ca-certificates/cacert.crt
RUN update-ca-certificates
