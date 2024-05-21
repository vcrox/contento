fresh:
	@make down
	@make build
	@make up 
	@make install-project
	# @make composer-update
	# @make npm-install
	# @make npm-build
setup:
	@make down
	@make build
down:
	docker-compose down
build:
	docker-compose build --no-cache --force-rm
stop:
	docker-compose stop
up:
	docker-compose up -d
install-project:
	docker exec contento-docker bash -c "git clone https://github.com/vcrox/contento.git ."
	@make composer-update
	@make npm-install
	@make npm-build
composer-update:
	docker exec contento-docker bash -c "composer update"
npm-install:
	docker exec contento-docker bash -c "npm update"
npm-build:
	docker exec contento-docker bash -c "npm run build"
data-migrate:
	docker exec contento-docker bash -c "php artisan migrate"
data-seed:
	docker exec contento-docker bash -c "php artisan db:seed"
data:
	docker exec contento-docker bash -c "php artisan migrate"
	docker exec contento-docker bash -c "php artisan db:seed"

# docker-compose build
#docker-compose  up
# docker exec -it contento-docker bash
# curl -s https://deb.nodesource.com/setup_22.x | bash
# apt install nodejs -y
#docker network disconnect bridge contento-docker
#docker network connect host contento-docker