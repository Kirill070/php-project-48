install:
	composer install

gendiff:
	./bin/gendiff

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer --verbose exec phpunit tests -- --coverage-clover build/logs/clover.xml