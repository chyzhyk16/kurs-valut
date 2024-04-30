init-local: dc-up composer-install

dc-up:
	cd docker; docker-compose up -d

composer-install:
	docker exec -t kurs-valut-php-fpm sh -c "cd backend; composer install"

exec-command:
	docker exec -t kurs-valut-php-fpm sh -c "cd backend; php bin/console app:check-exchange-rate"