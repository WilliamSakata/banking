DOCKER_RUN = docker run -u root --rm -i -v ${PWD}:/app -w /app gustavofreze/php:8.0.6-fpm

configure:
	@${DOCKER_RUN} composer update --optimize-autoloader

test:
	@${DOCKER_RUN} composer test tests

test-location:
	@${DOCKER_RUN} composer test ${LOCATION}

clean:
	rm -rf vendor report *.lock .phpunit.result.cache

change-owner:
	@sudo chown -R ${USER}:${USER} ${PWD}

up:
	docker-compose up -d

build:
	docker-compose build --no-cache bank-account

restart:
	docker-compose kill && docker-compose up -d