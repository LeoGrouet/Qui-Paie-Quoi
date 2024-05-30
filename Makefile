.PHONY=start
start:
	docker compose up -d

.PHONY=reset
reset:
	php app/bin/console do:da:dr -f
	php app/bin/console do:da:cr --no-interaction
	@make migrate

.PHONY=migrate
migrate:
	php app/bin/console do:mi:mi --no-interaction
