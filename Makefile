target=dev

init:
	#create default configuration files with default values
	sh init.sh

enter:
	#enter into the php container
	target=${target} docker-compose exec php bash

composer-install:
	#install composer dependencies
	target=${target} docker-compose exec php composer install

up:
	#run the project
	target=${target} docker-compose up -d

build-dev:
	#build php container for development
	target=dev docker-compose build php

build-prod:
	#build php container for production
	target=prod docker-compose build php

create-database-dev:
	#create database for development
	docker-compose exec php bin/console doctrine:database:create --env=dev

create-database-test:
	#create database for testing
	docker-compose exec php bin/console doctrine:database:create --env=test

functional-tests:
	#execute functional tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Functional

unit-tests:
	#execute unit tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Unit

all-tests:
	#execute all tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml

generate-migrations:
	#generate a new migration from the changes on the mapping files
	docker-compose exec php bin/console doctrine:migrations:diff

execute-migrations-test:
	#execute all the pending migrations for testing
	docker-compose exec php bin/console doctrine:migrations:migrate -n --env=test

execute-migrations-dev:
	#execute all the pending migrations for development
	docker-compose exec php bin/console doctrine:migrations:migrate -n --env=dev

coverage-unit:
	#generate the coverage for unit tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Unit --coverage-html coverage/unit

coverage-functional:
	#generate the coverage for functional tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Functional --coverage-html coverage/functional

coverage-all:
	#generate the coverage for all the tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --coverage-html coverage/all
