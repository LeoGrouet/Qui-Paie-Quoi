.PHONY=start
start:
	docker compose up -d
	
.PHONY=composer-install
composer-install:
	docker compose run --rm php composer install --no-scripts

.PHONY=reset
reset:
	docker compose run --rm php bin/console do:da:dr -f
	docker compose run --rm php bin/console do:da:cr --no-interaction
	@make migrate

.PHONY=migrate
migrate:
	docker compose run --rm php bin/console do:mi:mi --no-interaction

.PHONY=insert
insert:
	docker compose run --rm php bin/console app:insertInDB

.PHONY=handleBalance
handleBalance:
	docker compose run --rm php bin/console app:handle-balance

.PHONY=phpstan
phpstan:
	docker compose run --rm php vendor/bin/phpstan analyse src

.PHONY=phpcsfixer
phpcsfixer:
	docker compose run --rm php vendor/bin/php-cs-fixer fix src
	
.PHONY=phpcsfixer-dev
phpcsfixer-dev:
	docker compose run --rm php vendor/bin/php-cs-fixer check src

.PHONY=phpunit
phpunit:
	docker compose run --rm php bin/phpunit
