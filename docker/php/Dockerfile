FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
		acl \
		file \
		gettext \
		git \
#		nodejs \
#		yarn \
	;

RUN set -eux; \
	apt-get install -y \
		libicu-dev \
		libzip-dev \
	; \
	\
	docker-php-ext-configure zip --with-libzip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		zip \
		mysqli \
		pdo \
		pdo_mysql \
	; \ 
	pecl install \
		apcu-5.1.16 \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
    ;

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer