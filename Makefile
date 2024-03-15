up:
	docker-compose up -d
	symfony serve -d
	symfony open:local

stop:
	symfony server:stop
	docker-compose stop

restart:
	make stop
	make up

migration:
	symfony console make:migration
	symfony console doctrine:migrations:migrate

