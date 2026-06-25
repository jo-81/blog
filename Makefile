.PHONY: help install req req-dev test test-cover check lint fix ci

EXEC_PHP = docker compose exec app

# 1. On récupère tous les mots tapés après la première commande
ARGS = $(filter-out $@,$(MAKECMDGOALS))

# 2. Astuce pour éviter que Make ne lève une erreur en cherchant les packages comme cibles
%:
	@:

help:
	@echo "--- Commandes disponibles ---"
	@echo "  make install       - Installe les dependances Composer"
	@echo "  make req ...       - Ajoute une dependance (ex: make req vlucas/phpdotenv)"
	@echo "  make req-dev ...   - Ajoute une dependance de dev (ex: make req-dev fakerphp/faker)"
	@echo "  make test          - Lance les tests avec Pest"
	@echo "  make test-cover    - Lance les tests avec couverture (Xdebug)"
	@echo "  make check         - Analyse statique avec PHPStan"
	@echo "  make lint          - Verifie le style du code (PHP-CS-Fixer)"
	@echo "  make fix           - Corrige automatiquement le style"
	@echo "  make ci            - Execute toute la suite de qualite (Lint + Check + Test)"

## —— Dependances (Composer via Docker) ————————————————————————————————
install:
	$(EXEC_PHP) composer install

req:
	$(EXEC_PHP) composer require $(ARGS)

req-dev:
	$(EXEC_PHP) composer require $(ARGS) --dev

## —— Qualite du code & Tests (via Docker) ————————————————————————————
test:
	$(EXEC_PHP) vendor/bin/pest

test-cover:
	$(EXEC_PHP) XDEBUG_MODE=coverage vendor/bin/pest --coverage

check:
	$(EXEC_PHP) vendor/bin/phpstan analyse --memory-limit=-1

lint:
	$(EXEC_PHP) vendor/bin/php-cs-fixer check --diff

fix:
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix

ci: lint check test