DOCKER_RUN = docker run -u root --rm -i -v ${PWD}:/app -w /app php:8-fpm-alpine
FLYWAY = docker run --rm -v ${PWD}/db/migration/safe_adm:/flyway/sql/safe_adm -v ${PWD}/db/migration/safe_adm_test:/flyway/sql/safe_adm_test --env-file=ecs/local/configs.env --network=bco_default -e FLYWAY_EDITION="community" flyway/flyway:7.15.0

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