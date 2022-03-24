DOCKER_RUN = docker run --rm -i -v ${PWD}:/app -w /app williamsakata/bank:latest

configure:
	- @${DOCKER_RUN} composer update --optimize-autoloader

test:
	- @${DOCKER_RUN} composer test tests

test-location:
	- @${DOCKER_RUN} composer test ${LOCATION}

clean:
	- rm -rf vendor report *.lock .phpunit.result.cache

change-owner:
	- @sudo chown -R ${USER}:${USER} ${PWD}

up:
	- docker-compose up -d

build: change-owner
	- docker-compose build --no-cache bank-account

restart:
	- docker-compose kill && docker-compose up -d

check-style:
	- @${DOCKER_RUN} vendor/bin/phpcs --standard=PSR12 --exclude=PSR12.Files.FileHeader,PSR12.Files.OpenTag src/

fix-style:
	- @${DOCKER_RUN} vendor/bin/phpcbf --standard=PSR12 src/