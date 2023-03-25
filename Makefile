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

make-functional-tests:
	#execute functional tests
	docker-compose exec php php /app/bin/phpunit --bootstrap /app/tests/bootstrap.php --configuration /app/phpunit.xml --testsuite Functional
