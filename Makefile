up:
	docker-compose up -d
	symfony serve -d
	open https://127.0.0.1:8000/quiz/

stop:
	symfony server:stop
	docker-compose stop

restart:
	make stop
	make up

migration:
	symfony console make:migration
	symfony console doctrine:migrations:migrate

