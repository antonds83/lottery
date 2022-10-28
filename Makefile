service-run:
	docker-compose up
service-run-dev:
	docker-compose -f docker-compose-dev.yml up
service-stop:
	docker-compose down
	docker volume prune --force
service-clean:
	docker volume prune --force

service-remove-web:
	docker rmi antonds/lottery-web:latest
service-build-web:
	docker build --platform linux/amd64 -f df_web.Dockerfile -t antonds/lottery-web:latest .

service-remove-php:
	docker rmi antonds/lottery-php:7.4-fpm
service-build-php:
	docker build --platform linux/amd64 -f df_php.Dockerfile -t antonds/lottery-php:7.4-fpm .
