DOCKER_COMP = docker compose

PHP_CONT = $(DOCKER_COMP) exec php

PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
PHPUNIT  = $(PHP) bin/phpunit
DB_CONT = $(DOCKER_COMP) exec database
MYSQL_ROOT = $(DB_CONT) mysql -uroot -proot
DATABASE_URL_TEST = mysql://app:!ChangeMe!@database:3306/app?serverVersion=8.0.32&charset=utf8mb4



build:
	@$(DOCKER_COMP) build --pull --no-cache
up:
	@$(DOCKER_COMP) up -d
down:
	@$(DOCKER_COMP) down --remove-orphans
status:
	@$(DOCKER_COMP) ps
bash:
	@$(PHP_CONT) bash
chown:
	@$(PHP_CONT) chown -R $(id -u):$(id -g) .
composer:
	@$(COMPOSER) install
db-migrate:
	@$(SYMFONY) doctrine:migrations:migrate
test: configure-test
	@APP_ENV=test DATABASE_URL="$(DATABASE_URL_TEST)" $(PHPUNIT)
cc:
	@$(SYMFONY) cache:clear
load:
	@$(SYMFONY) doctrine:fixtures:load
configure-test:
	@APP_ENV=test DATABASE_URL="$(DATABASE_URL_TEST)" $(SYMFONY) doctrine:database:create --env=test --if-not-exists --no-interaction
	@APP_ENV=test DATABASE_URL="$(DATABASE_URL_TEST)" $(SYMFONY) doctrine:schema:drop --env=test --force --no-interaction
	@APP_ENV=test DATABASE_URL="$(DATABASE_URL_TEST)" $(SYMFONY) doctrine:schema:create --env=test --no-interaction
