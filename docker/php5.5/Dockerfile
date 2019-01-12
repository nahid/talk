FROM php:5.5-cli

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN apt-get update
RUN apt-get install -y ssh-client zip unzip git

WORKDIR /tmp

RUN composer self-update && \
	composer require "phpunit/phpunit:~4.8.36" && \
    ln -s /tmp/vendor/bin/phpunit /usr/local/bin/phpunit

VOLUME ["/app"]
WORKDIR /app
