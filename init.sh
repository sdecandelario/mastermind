#!/bin/bash

cp infra/mysql/.env.mysql.dist infra/mysql/.env.mysql
cp phpunit.xml.dist phpunit.xml
cp .env.local.dist .env.local
cp .env.test.local.dist .env.test.local
cp .php-cs-fixer.dist.php .php-cs-fixer.php
