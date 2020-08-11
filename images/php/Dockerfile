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

# Installiere Memcache
# @see https://www.php.net/manual/de/intro.memcache.php
RUN apt-get install -y \
		libz-dev \
		libmemcached-dev && \
	if echo "$PHP_VERSION" | grep -q "7.2" > /dev/null; then \
		pecl install \
			memcache; \
	else \
		pecl install \
			memcache-2.2.7; \
	fi && \
	docker-php-ext-enable \
		memcache

# Installiere OpCache
RUN	docker-php-ext-configure \
		opcache --enable-opcache && \
	docker-php-ext-install \
		opcache

# Installiere Module für die Bildbearbeitung
# @see https://www.php.net/manual/de/intro.image.php
RUN	apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		libwebp-dev && \
	docker-php-ext-configure \
		gd \
			--with-freetype-dir=/usr/include/ \
			--with-jpeg-dir=/usr/include/ \
			--with-webp-dir=/usr/include/ && \
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

# Clear
RUN	rm -rf /var/lib/apt/lists/*

# Installiere das CA-Zertifikat von der lokalen Entwickungsumgebung
COPY config/ssl/certs/cacert.crt /usr/local/share/ca-certificates/cacert.crt
RUN update-ca-certificates
