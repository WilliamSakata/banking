DOCKER_RUN = docker run --rm -it -v ${PWD}:/app -w /app gustavofreze/php:8.0.6

configure:
	@${DOCKER_RUN} composer update
	#@${DOCKER_RUN} composer update --optimize-autoloader --ignore-platform-reqs

test:
	@${DOCKER_RUN} composer test

clean:
	rm -rf vendor report *.lock .phpunit.result.cache

change-owner:
	@sudo chown -R ${USER}:${USER} ${PWD}

up:
	docker-compose up -d

build:
	docker-compose build --no-cache bank-account

#kill:
#	docker-compose kill
#
restart:
	docker-compose kill && docker-compose up -d

#logs:
#	docker-compose -f ./docker-compose.yml logs --tail=0 --follow


