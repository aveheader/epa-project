DOCKER_COMP = docker compose

PHP_CONT = $(DOCKER_COMP) exec php

PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console
PHPUNIT  = $(PHP) bin/phpunit
DB_CONT = $(DOCKER_COMP) exec database
MYSQL_ROOT = $(DB_CONT) mysql -uroot -proot


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
test:
	make configure-test
	@$(PHPUNIT)
cc:
	@$(SYMFONY) cache:clear
load:
	@$(SYMFONY) doctrine:fixtures:load
configure-test:
	@$(MYSQL_ROOT) -e "CREATE DATABASE IF NOT EXISTS app_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
	@$(MYSQL_ROOT) -e "GRANT ALL PRIVILEGES ON \`app_test\`.* TO 'app'@'%';"
	@$(MYSQL_ROOT) -e "FLUSH PRIVILEGES;"
	@$(SYMFONY) doctrine:migrations:migrate --no-interaction --env=test
