DOCKER_COMP = docker compose

PHP_CONT = $(DOCKER_COMP) exec php

PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
PHPUNIT  = $(PHP) bin/phpunit

build:
	@$(DOCKER_COMP) build --pull --no-cache
up:
	@$(DOCKER_COMP) up --detach --wait
down:
	@$(DOCKER_COMP) down --remove-orphans
bash:
	@$(PHP_CONT) bash
chown:
	@$(PHP_CONT) chown -R $(id -u):$(id -g) .
composer:
	@$(COMPOSER) install
db-migrate:
	@$(SYMFONY) doctrine:migrations:migrate
test:
	make configure-test
	@$(PHPUNIT)
cc:
	@$(SYMFONY) cache:clear
load:
	@$(SYMFONY) doctrine:fixtures:load
configure-test:
	@$(SYMFONY) doctrine:database:create --if-not-exists --env=test
	@$(SYMFONY) doctrine:migrations:migrate --no-interaction --env=test
