.PHONY: help start stop restart test test-coverage clean phpstan phpcs phpcbf

help:
	@echo "Available commands:"
	@echo "  make start          - Start all containers"
	@echo "  make stop           - Stop all containers"
	@echo "  make restart        - Restart all containers"
	@echo "  make test           - Run tests"
	@echo "  make test-coverage  - Run tests with coverage report"
	@echo "  make clean          - Clean all containers and volumes"
	@echo "  make phpstan        - Run PHPStan analysis"
	@echo "  make phpcs          - Run PHP Code Sniffer"
	@echo "  make phpcbf         - Fix PHP Code Sniffer violations"

start:
	@chmod +x docker/scripts/*.sh
	@./docker/scripts/start.sh

stop:
	@chmod +x docker/scripts/*.sh
	@./docker/scripts/stop.sh

restart: stop start

test:
	@chmod +x docker/scripts/*.sh
	@./docker/scripts/test.sh

test-coverage:
	@docker compose run --rm test php vendor/bin/phpunit --coverage-html coverage

clean:
	@chmod +x docker/scripts/*.sh
	@./docker/scripts/clean.sh

phpstan:
	@docker compose run --rm app vendor/bin/phpstan analyse src tests --level=8

phpcs:
	@docker compose run --rm app vendor/bin/phpcs --standard=PSR12 src tests

phpcbf:
	@docker compose run --rm app vendor/bin/phpcbf --standard=PSR12 src tests