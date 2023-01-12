.PHONY: up down logs status send-to-device start-consumer send-from-device start-device-listener help
.DEFAULT_GOAL := help
run-docker-compose = docker compose -f docker-compose.yml
run-php = docker-compose run php php
run-composer = docker-compose run php composer

up: # Start containers and tail logs
	$(run-docker-compose) up -d
	make logs

down: # Stop all containers
	$(run-docker-compose) down --remove-orphans

logs: # Tail container logs
	$(run-docker-compose) logs -f kafka zookeeper

status: # Show status of all containers
	$(run-docker-compose) ps

composer-install: # Install composer dependencies
	$(run-composer) install

send-to-device: # Send message to device
	$(run-php) bin/console app:send-to-device "$(payload)"

start-device-listener: # Listen to device
	$(run-php) bin/device_listener.php

send-from-device: # Send message from device
	$(run-php) bin/device_sender.php

start-consumer: # Start command to consume messages
	$(run-php) bin/console messenger:consume consumer -vv

help: # make help
	@awk 'BEGIN {FS = ":.*#"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?#/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)
