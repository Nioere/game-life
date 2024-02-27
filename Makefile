PHP := php
COMPOSER := composer
DOCKER_PHP_FPM := docker compose exec php-fpm

start:
	php bin/console GameLife
