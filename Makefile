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
install: ## Install composer and yarn
	$(MAKE_DOCKER) composer-install
	$(MAKE_DOCKER) yarn-install
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

##—— Yarn 🐱 ———————————————————————————————————————————————————————————————————
yarn-install: ## Install vendors according to the current yarn.lock file
	$(MAKE_DOCKER) yarn-install
yarn-install-no-tty: ## Alias yarn-install without interaction
	$(MAKE_DOCKER) yarn-install-no-tty

##—— Symfony && CLI 🎵 ———————————————————————————————————————————————————————————————
sf: ## Symfony CLI helps developers manage projects, from local code to remote infrastructure
	$(MAKE_DOCKER) sf
sf-cli: ## Runs the Symfony Console (bin/console) for current project
	$(MAKE_DOCKER) symfony $(COMMAND_ARGS)
node: ## Node CLI
	$(MAKE_DOCKER) node-bash
php: ## PHP CLI
	$(MAKE_DOCKER) php-bash
phpqa: ## PHPQA CLI
	$(PHPQA) bash

##—— Maker 🏭 ———————————————————————————————————————————————————————————————
entity: ## Create entity resources
	$(MAKE_DOCKER) symfony ADD_ENV="MAKER_NAMESPACE='Doctrine2'" make:entity --force-annotation
migration: ## Create migration
	$(MAKE_DOCKER) symfony make:migration
controller: ## Create controller resources
	$(MAKE_DOCKER) symfony make:controller

##—— Quality of Analysis ✅ —————————————————————————————————————————————————————————————————
php-cs: ## The PHP Coding Standards Fixer tool fixes your code to follow standards
	@echo '----🧪 PHP Coding Standards Fixer -----------'
	@$(PHPQA_NOTTY) php-cs-fixer fix
phpstan: ## PHPStan focuses on finding errors in your code without actually running it.
	@echo '----🧪 PHP Static Analysis Tool ------------------'
	@$(PHPQA) phpstan analyse -l 0 src tests

##—— Tests ✅ —————————————————————————————————————————————————————————————————
test: ## Run test PHP-CS / Security:check / Testing / Infection / PHPStan
	@echo '---- 🏁 START TEST ------------'
	@echo '----🧪 PHP Coding Standards Fixer -----------'
	@$(PHPQA) php-cs-fixer fix
	@echo '----🧪 Security Checker -------'
	@$(PHPQA) symfony security:check
	@echo '----🧪 The PHP Testing ---------------'
	@$(PHPQA) php -d pcov.enabled=1 ./bin/phpunit 
# @echo '----🧟 The PHP Muta Testing ---------------'
# @$(PHPQA) /tools/infection run --initial-tests-php-options='-dpcov.enabled=1' --coverage=build/coverage
	@echo '----🧪 PHP Static Analysis Tool ------------------'
	@$(PHPQA) phpstan analyse -l 0 src tests
	@echo '---- ENDED TEST ---------------'

tu-file:
	@$(PHPQA) php -d pcov.enabled=1 ./bin/phpunit $(COMMAND_ARGS)

tu:
	@echo '----🧪 The PHP Testing ---------------'
	@$(PHPQA) php -d pcov.enabled=1 ./bin/phpunit --testsuite $(COMMAND_ARGS)

reload:
	$(MAKE_DOCKER) reload

