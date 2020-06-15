.DEFAULT_GOAL := help
.SILENT:
.PHONY: vendor

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## User
UID=$(id -u)
GID=$(id -g)
DOCKER_PHP_SERVICE=php

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

##################
# Useful targets #
##################

## Install all install_* requirements and launch project.
install: fix_symfony_directories env_run install_vendor
## Run project, install vendors and run migrations.
run: env_run install_vendor install_db
## Stop project.
stop:
	docker-compose stop

## Down project and remove volumes (databases).
down:
	docker-compose down -v --remove-orphans

## Run all quality assurance tools (tests and code inspection).
tests: test test_behaviour

## Truncate database and import fixtures.
reset: down run import_dev

## Create Symfony folders & Fix permission
fix_symfony_directories:
	sudo $(shell mkdir -p var var/cache var/log) chmod -R 777 var/log && echo "${COLOR_INFO} Creating Symfony folders & Fixing permission ✔ ✅ 😊 ${COLOR_RESET}\n"

## Launch docker environment.
env_run:
	docker-compose up -d

###############
# Import Data #
###############

## Import fixtures.
import_dev:
	docker-compose exec --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:fixtures:load -n

###########
# Install #
###########

## Run database migration.
install_db:
	docker-compose run --rm --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:migrations:migrate -n

## Run test database migration.
install_db_test:
	docker-compose run --rm --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console doctrine:migrations:migrate -n --env=test

## Install vendors.
install_vendor:
	docker-compose run --rm --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer install --prefer-dist

update_vendor:
	docker-compose run --rm --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer update --prefer-dist


########
# Test #
########

## Run unit&integration tests with pre-installing test database.
test: install_db_test test_unit


## Run behaviour tests.
test_behaviour:
	docker-compose exec --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} vendor/bin/behat $(file) --colors

## Show behat steps list.
test_behaviour_list:
	docker-compose exec --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} vendor/bin/behat -dl

## Run unit&integration tests.
test_unit:
	docker-compose exec --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} vendor/bin/phpunit

## Run unit&integration tests with coverage
test_unit_coverage:
	docker-compose exec --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} vendor/bin/phpunit --coverage-text


#########
# Utils #
#########

## Update migrations
db_diff:
	docker-compose run --user ${UID}:${GID} --rm ${DOCKER_PHP_SERVICE} bin/console doctrine:migrations:diff -n

## Update migrations && Install DB
db_update: db_diff install_db

#########|
# OTHERS #
#########|

## Open sh as normal user
sh:
	docker-compose run --user ${UID}:${GID} --rm ${DOCKER_PHP_SERVICE} sh

## Open sh as root
sh_root:
	docker-compose run --rm ${DOCKER_PHP_SERVICE} sh

## Clear Symfony Cache
symfony_cache_clear:
	docker-compose run --rm --user ${UID}:${GID} ${DOCKER_PHP_SERVICE} bin/console cache:clear
