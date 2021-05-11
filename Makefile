# Function —————————————————————————————————————————————————————————————————————
define isset_env_file
    -include $(1) ; export $(shell [ -e "$(1)" ] && sed 's/=.*//' $(1))
endef

### Setup ——————————————————————————————————————————————————————————————————————
MAKE_DOCKER := cd .docker && $(MAKE) -s
MAKEPATH := $(abspath $(lastword $(MAKEFILE_LIST)))
pwd := $(dir $(MAKEPATH))

PHPQA := docker run --init -it --rm -v "$(pwd):/project" -v "$(pwd)/var/phpqa:/tmp" -w /project tools/phpqa
PHPQA_NOTTY := docker run --init -i --rm -v "$(pwd):/project" -v "$(pwd)/var/phpqa:/tmp" -w /project tools/phpqa

SUPPORTED_COMMANDS := sf-cli tu-file
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  COMMAND_ARGS := $(subst :,\:,$(COMMAND_ARGS))
  $(eval $(COMMAND_ARGS):;@:)
endif

.SILENT: help run sf-cli entity migration controller use-case


help:
	@printf "\033[33mUsage:\033[0m\n  make TARGET\n\n\033[32m—— 🐝 The Project Abbeal Symfony Makefile 🐝 ——————————————————————————————————\033[0m\n\n"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | awk 'BEGIN {FS = ":"}; {printf "\033[33m%s:\033[0m%s\n", $$1, $$2}'
wait:
	sleep 5

##—— Docker 🐳 —————————————————————————————————————————————————————————————————
install: ## Install composer
	$(MAKE_DOCKER) composer-install
run: ## Build and up container
	$(MAKE_DOCKER) build
	$(MAKE_DOCKER) up
stop: ## Down and clean container
	$(MAKE_DOCKER) down
	$(MAKE_DOCKER) clean

##—— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
composer-install: ## Install vendors according to the current composer.lock file
	$(MAKE_DOCKER) composer-install
composer-install-no-tty: ## Alias composer-install without interaction
	$(MAKE_DOCKER) composer-install-no-tty


##—— Symfony && CLI 🎵 ———————————————————————————————————————————————————————————————
sf: ## Symfony CLI helps developers manage projects, from local code to remote infrastructure
	$(MAKE_DOCKER) sf
sf-cli: ## Runs the Symfony Console (bin/console) for current project
	$(MAKE_DOCKER) symfony $(COMMAND_ARGS)
php: ## PHP CLI
	$(MAKE_DOCKER) php-bash


##—— Maker 🏭 ———————————————————————————————————————————————————————————————
entity: ## Create entity resources
	$(MAKE_DOCKER) symfony ADD_ENV="MAKER_NAMESPACE='Doctrine2'" make:entity --force-annotation
migration: ## Create migration
	$(MAKE_DOCKER) symfony make:migration
migrate: ## Create migration
	$(MAKE_DOCKER) symfony do:mi:mi
fixtures: ## Create migration
	$(MAKE_DOCKER) symfony do:fix:lo
controller: ## Create controller resources
	$(MAKE_DOCKER) symfony make:controller

reload:
	$(MAKE_DOCKER) reload

