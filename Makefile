.PHONY=start
start:
	docker compose up -d

.PHONY=reset
reset:
	docker compose run --rm php php bin/console do:da:dr -f
	docker compose run --rm php php bin/console do:da:cr --no-interaction
	@make migrate

.PHONY=migrate
migrate:
	docker compose run --rm php php bin/console do:mi:mi --no-interaction

.PHONY=insert
insert:
	docker compose run --rm php bin/console app:insertInDB

.PHONY=phpstan
phpstan:
	docker compose run --rm php vendor/bin/phpstan analyse src
