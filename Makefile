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
#chmod 777 storage
#php artisan migrate:fresh --seed
#/etc/init.d/apache2 restart
#docker export contento-docker > c:\contento-docker.tar

#dnf config-manager --add-repo=https://download.docker.com/linux/centos/docker-ce.repo
#dnf install docker-ce docker-ce-cli containerd.io
#systemctl enable --now docker
#firewall-cmd --zone=public --add-masquerade --permanent
#firewall-cmd --reload

#curl -L "https://github.com/docker/compose/releases/download/1.27.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
#chmod +x /usr/local/bin/docker-compose
#usermod -aG docker username
#docker run hello-world