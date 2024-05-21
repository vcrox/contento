# Initialize an empty array to store image names
$images = @()

# Retrieve the list of images from the Docker Compose configuration
$dockerImages = docker compose config --images

# Iterate over each image and add it to the array
foreach ($img in $dockerImages) {
    $images += $img
}

# Print each image name to verify correctness
foreach ($image in $images) {
    Write-Output "Image: $image"
}

# Save each Docker image to individual files and then combine them into a single tar file
$tarFiles = @()
foreach ($image in $images) {
    $fileName = "$($image.Replace(':', '_')).tar"
    docker save -o $fileName $image
    $tarFiles += $fileName
}

# Combine the individual tar files into a single tar file
$combinedTarFile = "services.img"
Remove-Item -Path $combinedTarFile -ErrorAction Ignore
foreach ($file in $tarFiles) {
    tar -rf $combinedTarFile -C (Split-Path $file) (Split-Path $file -Leaf)
}

# Cleanup individual tar files
foreach ($file in $tarFiles) {
    Remove-Item -Path $file
}
