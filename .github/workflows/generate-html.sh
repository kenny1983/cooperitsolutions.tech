#!/bin/bash

# Create a symbolic link to the current directory
# if it doesn't exist
if [[ ! -L "/workspace" ]]; then
    echo "Creating symbolic link /workspace to $(pwd)"
    ln -s "$(pwd)" /workspace
fi

# Check if php-fpm is running
if ! pgrep "php-fpm" >/dev/null; then
    php-fpm -D
fi

# Check if nginx is running
if ! pgrep "nginx" >/dev/null; then
    service nginx start
fi

# Curl the URL $1 and save it to $2
function curl_and_save() {
    echo "curl_and_save https://localhost$1 > $2"
    curl -ks --fail "https://localhost$1" >"$2"

    # Check if file exists
    if [[ ! -e "$2" ]]; then
        echo "Error: File '$2' does not exist."
        exit 1
    fi

    # Check if file size is zero-length
    if [[ ! -s "$2" ]]; then
        echo "Error: File '$2' is empty."
        exit 1
    fi
}

#region Building HTML files
rm -rf dist
mkdir -p dist
chmod 777 tmp

touch build.lock # we are running the build process.
trap "rm -rf build.lock" EXIT

curl_and_save "/" "dist/index.html"

# **/*.php Any php file dist Building
find . -name "*.php" ! -path "./system/*" ! -path "./vendor/*" ! -path "./layout/*" | while read -r file; do
    file="${file#./}" #Remove the leading ./
    without_extension="${file%.*}"

    # Replace the .php extension with .html
    dist_file="dist/${file%.php}.html"

    # Create the directory structure if it doesn't exist
    mkdir -p "$(dirname "$html_file")"

    filename=$(basename "$file" .php)
    curl_and_save "/$without_extension" "$html_file"
done

#endregion

# copying assets to ./dist
cp -R css dist/css
cp -R images dist/images
cp -R js dist/js
cp -R node_modules dist/node_modules