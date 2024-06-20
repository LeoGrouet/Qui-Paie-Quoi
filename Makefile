.PHONY=start
start:
	docker compose up -d

.PHONY=reset
reset:
	php App/bin/console do:da:dr -f
	php App/bin/console do:da:cr --no-interaction
	@make migrate

.PHONY=migrate
migrate:
	php App/bin/console do:mi:mi --no-interaction
