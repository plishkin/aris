#!/usr/bin/env bash

docker compose stop
docker compose down

sudo rm -rf silverstripe-cache
sudo rm -rf assets
sudo rm -rf storage
sudo rm -rf vendor
sudo rm -rf themes
sudo rm -rf .composer

mkdir -p silverstripe-cache
cp -r docker/storage .
cp -r docker/assets .

sudo chmod 0777 .

docker compose build
docker compose up -d db
docker compose up -d fpm
docker compose exec fpm composer install --optimize-autoloader
docker compose exec db bash mysql_last.sh
docker compose exec fpm php framework/cli-script.php /dev/build flush=1

docker compose up -d #--remove-orphans

sudo chmod -R 0777 silverstripe-cache storage assets vendor
