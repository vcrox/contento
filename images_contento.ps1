# Path to your docker-compose.yml file
$composeFile = "docker-compose.yml"

# Parse the YAML file to get service names
$services = (docker-compose -f $composeFile config --services)

foreach ($service in $services) {
    # Get the container ID for the service
    $containerId = docker-compose -f $composeFile ps -q $service

    if ($containerId) {
        # Export and compress the container
        docker export $containerId | bash -c "gzip > ${service}.gz"
        Write-Output "Exported and compressed $service (Container ID: $containerId) to ${service}.gz"
    } else {
        Write-Output "Service $service is not running or does not have a container."
    }
}