# @see https://hub.docker.com/_/php
FROM php:7.2-fpm-stretch
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
		gettext && \
# Installiere XDebug
	pecl install \
		xdebug-2.9.2 && \
	docker-php-ext-enable \
		xdebug && \
# Installiere MySQL
# @see https://www.php.net/manual/de/intro.mysql.php
	apt-get install -y \
		mysql-client && \
	docker-php-ext-install \
		pdo_mysql \
		mysqli && \
# Installiere Memcache
# @see https://www.php.net/manual/de/intro.memcache.php
	apt-get install -y \
		libz-dev \
		libmemcached-dev && \
	pecl install \
		memcache && \
	docker-php-ext-enable \
		memcache && \
# Installiere OpCache
	docker-php-ext-configure \
		opcache --enable-opcache && \
	docker-php-ext-install \
		opcache && \
# Installiere Module für die Bildbearbeitung
# @see https://www.php.net/manual/de/intro.image.php
	apt-get install -y \
		libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev && \
	docker-php-ext-configure \
		gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) \
		gd && \
# Installiere Socket-Modul
# @see https://www.php.net/manual/en/intro.sockets.php
	docker-php-ext-install sockets && \
# Installiere Metadaten von Bildern
	docker-php-ext-install \
		exif && \
	docker-php-ext-enable \
		exif && \
# Clear
	rm -rf /var/lib/apt/lists/*

# Die Ports für FCGI bereitsstellen
EXPOSE 9000
