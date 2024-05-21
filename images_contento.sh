#!/bin/bash

# Path to your docker-compose.yml file
COMPOSE_FILE="docker-compose.yml"

# Get a list of all service names
services=$(docker-compose -f "$COMPOSE_FILE" config --services)

# Loop through each service
for service in $services; do
    # Get the container ID for the service
    container_id=$(docker-compose -f "$COMPOSE_FILE" ps -q $service)

    if [ -n "$container_id" ]; then
        # Export and compress the container
        docker export "$container_id" | gzip > "${service}.gz"
        echo "Exported and compressed $service (Container ID: $container_id) to ${service}.gz"
    else
        echo "Service $service is not running or does not have a container."
    fi
done