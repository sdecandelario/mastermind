enter:
	#enter into the php container
	docker-compose exec php bash

composer-install:
	#install composer dependencies
	docker-compose exec php composer install

up:
	#run the project
	docker-compose up -d

build:
	#build php container
	docker-compose build php

functional-tests:
	#execute functional tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Functional

unit-tests:
	#execute functional tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Unit

generate-migration:
	#generate a new migration from the changes on the mapping files
	docker-compose exec php bin/console doctrine:migrations:diff

execute-migrations-test:
	#execute all the pending migrations
	docker-compose exec php bin/console doctrine:migrations:migrate -n --env=test

execute-migrations-dev:
	#execute all the pending migrations
	docker-compose exec php bin/console doctrine:migrations:migrate -n --env=dev
