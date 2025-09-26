ifeq (,$(wildcard .env))
PACKAGE_NAME=$(shell grep '"name"' ../composer.json | head -n 1 | sed -E 's/.*"name": *"([^"]*)".*/\1/' | cut -d'/' -f2)
$(shell echo "PROJECT_NAME=$(PACKAGE_NAME)" > .env)
endif

include .env

# ----- FONT COLOR VARIABLES ----- #
C_DEFAULT 		= \033[39m
C_GREEN   		= \033[32m
C_YELLOW  		= \033[33m
C_LIGHT_YELLOW	= \033[93m

# ----- BG COLOR VARIABLES ----- #
BGC_DEFAULT	= \033[49m
BGC_CYAN	= \033[46m

# ----- VARIABLES  ----- #
# USER
DOCKER_UID 		?= $(shell id -u)
DOCKER_GID 		?= $(shell id -g)
DOCKER_USERNAME ?= $(shell whoami)
# CONTAINER NAMES
COMPOSER_CONTAINER_NAME 	= "${PROJECT_NAME}-composer-container"
MEGALINTER_CONTAINER_NAME 	= "${PROJECT_NAME}-megalinter-container"
# COMPOSE
DOCKER_ARGS			= DOCKER_USERNAME="${DOCKER_USERNAME}" DOCKER_UID="${DOCKER_UID}" DOCKER_GID="${DOCKER_GID}" PROJECT_NAME="${PROJECT_NAME}"
DOCKER_COMPOSE		= DOCKER_USER="${DOCKER_USERNAME}" ${DOCKER_ARGS} docker compose -f Docker/Composer/docker-compose.yml
DOCKER_MEGALINTER	= REPORT_OUTPUT_FOLDER=none  PROJECT_NAME="${PROJECT_NAME}" docker compose -f Docker/Megalinter/docker-compose.yml
# COMPOSER CONTAINER
CONTAINER_EXEC			= docker exec -it "${COMPOSER_CONTAINER_NAME}"
MEGALINTER_EXEC			= docker exec -it "${MEGALINTER_CONTAINER_NAME}"
COMPOSER_CONTAINER_ID	:= $(shell docker images --filter reference=${PROJECT_NAME}-composer-container -aq)

# ----- TARGETS  ----- #
.DEFAULT_GOAL := help

# ----- SCRIPTS ----- #
COMPOSER_NORMALIZE = "composer normalize - --no-check-lock"
COMPOSER_VALIDATE = "composer validate --strict --no-check-lock"
PHP_CS_FIXER = "php vendor/bin/php-cs-fixer fix --config .php-cs-fixer.dist.php -v  --using-cache no --diff"
PHP_MD = "php -r \"error_reporting(error_reporting() ^ E_DEPRECATED);\"; php ../vendor/bin/phpmd ../Classes/ text ../gsb11.ruleset.xml --baseline-file ../phpmd.baseline.xml -vvv"
PHP_STAN = "php vendor/bin/phpstan analyse --memory-limit=256M --no-progress"
PHP_UNIT = "vendor/bin/phpunit -c Tests/UnitTests.xml --testdox --coverage-html Tests/_output"
RECTOR = "vendor/bin/rector --dry-run"
TYPOSCRIPT_LINT = "php vendor/bin/typoscript-lint -c .typoscript-lint.yml --ansi -n --fail-on-warnings -vvv"

### General:
#help: ## Displays this list of possible commands with descriptions
.PHONY: help
help:
	@awk 'BEGIN { \
			FS = ":.*##"; \
			printf "\n$(C_YELLOW)Usage:$(C_DEFAULT)\n  make $(C_GREEN)<command>$(C_DEFAULT) (multiple commands with the same purpose are seperated by $(C_GREEN)/$(C_DEFAULT)) \n"; \
		} \
		\
		/^#.*?:.*?##/ { \
			printf "  $(C_GREEN)%-30s$(C_DEFAULT) %s\n", substr($$1, 2), $$2 \
		} \
		\
		/^###/ { \
			printf "\n$(C_YELLOW)%s$(C_DEFAULT)\n", substr($$0, 5) \
		} ' $(MAKEFILE_LIST)

#set-itzbund-auth-token: ## Creates auth.json for the gsb-itzbund repositories with the given token
.PHONY: set-itzbund-auth-token
set-itzbund-auth-token:
	@read -p "Enter your gsb-itzbund Access Token: " enter; $(MAKE) container exec="composer config gitlab-token.git.gsb-itzbund.de $$enter" --no-print-directory


### Container:
#build: ## build docker images
.PHONY: build
build:
	@COMPOSE_BAKE=true DOCKER_USER="root" ${DOCKER_ARGS} docker buildx bake --file Docker/Composer/docker-compose.yml --no-cache --provenance=false

#container: ## Execute given 'exec' parameter as command in container (e.g. "make container exec='composer list'" runs "composer list" inside the container)
.PHONY: container
container:
	@if [ -n "$(COMPOSER_CONTAINER_ID)" ]; then \
		$(DOCKER_COMPOSE) up -d; \
		$(CONTAINER_EXEC) $(exec); \
		$(DOCKER_COMPOSE) down; \
	else \
		COMPOSE_BAKE=true DOCKER_USER="root" $(DOCKER_ARGS) docker buildx bake --file Docker/Composer/docker-compose.yml --no-cache; \
		$(DOCKER_COMPOSE) up -d; \
		$(CONTAINER_EXEC) composer install --no-interaction --prefer-dist; \
		$(CONTAINER_EXEC) $(exec); \
		$(DOCKER_COMPOSE) down; \
	fi

#install: ## Run composer install for vendor folder
.PHONY: install
install:
	@$(MAKE) container exec='composer install --no-interaction --prefer-dist' --no-print-directory

#update: ## Run composer update for vendor folder
.PHONY: update
update:
	@$(MAKE) container exec='composer update --no-interaction --prefer-dist' --no-print-directory


### Collections:
#run-linting: ## Execute all linting scripts except Megalinter
.PHONY: run-linting
run-linting:
	@$(MAKE) composer-normalize --no-print-directory
	@$(MAKE) typoscript-lint  --no-print-directory
	@$(MAKE) phpcs --no-print-directory
	@$(MAKE) phpmd --no-print-directory
	@$(MAKE) phpstan --no-print-directory

#run-megalinter: ## Executes Megalinter
.PHONY: run-megalinter
run-megalinter:
	@${DOCKER_MEGALINTER} up -d
	@docker cp ${MEGALINTER_CONTAINER_NAME}:"/src/" ../megalinter-yaml
	@find ../megalinter-yaml -type f -exec mv -nt ../. {} +; rm -rf ../megalinter-yaml
	@${MEGALINTER_EXEC} bash "/entrypoint.sh"
	@${DOCKER_MEGALINTER} down

#run-tests: ## Execute all test scripts
.PHONY: run-tests
run-tests: rector phpunit


### Tests:
#rector: ## Execute Rector PHP to refactor the codebase
.PHONY: rector
rector:
	@$(MAKE) container exec=${RECTOR} --no-print-directory

#phpunit: ## Execute PHPUnit tests
.PHONY: phpunit
phpunit:
	@$(MAKE) container exec=${PHP_UNIT} --no-print-directory


### Linting Scripts:
#composer-normalize: ## Normalizes composer.json
.PHONY: composer-normalize
composer-normalize:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                                       $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    -------------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running composer-normalize |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    -------------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                                       $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${COMPOSER_NORMALIZE} --no-print-directory

#composer-validate: ## Validate composer.json
.PHONY: composer-validate
composer-validate:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                                      $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running composer-validate |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                                      $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${COMPOSER_VALIDATE} --no-print-directory

#phpcs: ## Analyze code with PHP Code Sniffer (TYPO3 Ruleset)
.PHONY: phpcs
phpcs:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                          $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running phpcs |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                          $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${PHP_CS_FIXER} --no-print-directory

#phpmd: ## Analyze code with PHPMD
.PHONY: phpmd
phpmd:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                          $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running phpmd |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                          $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${PHP_MD} --no-print-directory

#phpstan: ## Analyze code with PHPStan
.PHONY: phpstan
phpstan:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                            $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    --------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running phpstan |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    --------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                            $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${PHP_STAN} --no-print-directory

#typoscript-lint: ## Lint TypoScript files
.PHONY: typoscript-lint
typoscript-lint:
	@printf "\n  $(BGC_CYAN)$(C_LIGHT_YELLOW)                                    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ----------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    |  Running typoscript-lint |    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)    ----------------------------    $(BGC_DEFAULT)\n"
	@printf "  $(BGC_CYAN)                                    $(C_DEFAULT)$(BGC_DEFAULT)\n\n"
	@$(MAKE) container exec=${TYPOSCRIPT_LINT} --no-print-directory
