#!/bin/bash

docker compose up -d

docker compose exec app composer install

docker compose exec app php scripts/migrate.php

echo "Application is running!"
echo "API: http://localhost:8080"
echo "phpMyAdmin: http://localhost:8081"
echo "Run tests: docker compose run test"