.PHONY=start
start:
	docker compose up -d

.PHONY=reset
reset:
	php bin/console do:da:dr -f
	php bin/console do:da:cr --no-interaction
	@make migrate

.PHONY=migrate
migrate:
	php bin/console do:mi:mi --no-interaction
