ADD_ENV ?= "FOO=BAR"

MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
pwd := $(dir $(MAKEPATH))

PHP_SERVICE := php
NODE_SERVICE:= node

COMPOSE := docker-compose --env-file ../.env.dev.local -f docker-compose.yml -f docker-compose.local.yml

PHP := @$(COMPOSE) exec $(PHP_SERVICE)
NODE := @$(COMPOSE) exec $(NODE_SERVICE)

.DEFAULT_GOAL = help
.PHONY: up reset-db reset-all

SUPPORTED_COMMANDS := require yarn-add require-dev yarn-add-dev symfony
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  COMMAND_ARGS := $(subst :,\:,$(COMMAND_ARGS))
  $(eval $(COMMAND_ARGS):;@:)
endif

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Up container local
	@$(COMPOSE) up -d

down:
	@$(COMPOSE) down

build:
	@$(COMPOSE) build
	@docker build -t tools/phpqa ./tools/phpqa

reset-all:
	@$(COMPOSE) down -v --remove-orphans

clean: ## Clean volumes
	@docker system prune --volumes --force

## —— Composer 🧙‍♂️ ——————————————————————————————————————————————————————————————
composer-install: ../composer.lock ## Install vendors according to the current composer.lock file
	@$(COMPOSE) run --rm $(PHP_SERVICE) composer install --no-progress --prefer-dist --optimize-autoloader

composer-install-no-tty: ../composer.lock ## Alias composer-install without interaction
	@$(COMPOSE) exec -T $(PHP_SERVICE) composer install --no-progress --prefer-dist --optimize-autoloader --no-interaction

update: ../composer.json ## Update vendors according to the composer.json file
	@$(COMPOSE) run --rm $(PHP_SERVICE) composer update

require:
	@$(COMPOSE) run --rm $(PHP_SERVICE) composer require --prefer-source --prefer-stable $(COMMAND_ARGS)

require-dev:
	@$(COMPOSE) run --rm $(PHP_SERVICE) composer require --prefer-source --dev $(COMMAND_ARGS)

## —— Yarn 🐱 ——————————————————————————————————————————————————————————————
yarn-install: ../yarn.lock ## Install vendors according to the current yarn.lock file
	@$(COMPOSE) run --rm $(NODE_SERVICE) yarn install --force

yarn-install-no-tty: ../yarn.lock ## Alias composer-install without interaction
	@$(COMPOSE) exec -T $(NODE_SERVICE) yarn install --non-interactive

yarn-add: ## Install vendors according to the current yarn.lock file
	@$(COMPOSE) run --rm $(NODE_SERVICE) yarn add $(COMMAND_ARGS)

yarn-add-dev: ## Install vendors according to the current yarn.lock file
	@$(COMPOSE) run --rm $(NODE_SERVICE) yarn add --dev $(COMMAND_ARGS)

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	@$(PHP) symfony

## —— Symfony binary 🕹️ ————————————————————————————————————————————————————————
symfony:
	@$(COMPOSE) exec --env $(ADD_ENV) $(PHP_SERVICE) symfony console $(COMMAND_ARGS)

reload:
	@$(PHP) symfony console cache:clear
	@$(PHP) symfony console doctrine:database:drop --force --if-exists
	@$(PHP) symfony console doctrine:database:create --if-not-exists
	@$(PHP) symfony console doctrine:migrations:migrate --no-interaction --allow-no-migration
	@$(PHP) symfony console doctrine:fixtures:load --purge-with-truncate --no-interaction

## —— PHP Container 💻 ————————————————————————————————————————————————————————
php-bash: ## Connect to the application container
	@$(COMPOSE) run --rm $(PHP_SERVICE) bash

## —— Node Container 💻 ———————————————————————————————————————————————————————————————
node-bash:
	@$(COMPOSE) run --rm $(NODE_SERVICE) bash

node-build:
	@$(COMPOSE) run --rm $(NODE_SERVICE) npm run build

## —— Redis CLI 💻 ———————————————————————————————————————————————————————————————
redis-cli:
	@$(COMPOSE) exec -T $(REDIS_SERVICE) redis-cli
