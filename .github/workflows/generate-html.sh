#!/bin/bash

# Create a symbolic link to the current
# directory if one doesn't already exist
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
    phpFile=$(printf '%s\n' "$1" | sed 's/[.[\*^$(){}?+|/\\]/\\&/g')
    htmlFile=$(printf '%s\n' "$2" | sed 's/[.[\*^$(){}?+|/\\]/\\&/g')

    echo "curl_and_save https://localhost$1 > $2"
    curl -ks --fail "https://localhost$1" | sed "s/$phpFile/$htmlFile/g" > "$2"

    # Ensure file exists
    if [[ ! -e "$2" ]]; then
        echo "Error: File '$2' does not exist."
        exit 1
    fi

    # Ensure file is not zero-length
    if [[ ! -s "$2" ]]; then
        echo "Error: File '$2' is empty."
        exit 1
    fi
}

#region Building HTML files
rm -rf dist
mkdir -p dist/sites
chmod 777 tmp

touch build.lock # we are running the build process.
trap "rm -rf build.lock" EXIT

# **/*.php (any PHP file) HTML Building
find ./pages -name "*.php" | while read -r file; do
    path="${file#./}" # Remove the leading ./
    file="$(basename "$path")"

    # Replace the .php extension with .html and prepend dist/
    htmlFile="dist/${file%.php}.html"

    # Create the directory structure if it doesn't exist
    mkdir -p "$(dirname "$htmlFile")"

    # Hit the PHP script with cURL and save the resulting HTML
    curl_and_save "/$path" "$htmlFile"
done

# Rename the home page's HTML file to index.html
# so that it loads when visiting the site root
mv dist/home.html dist/index.html

# Grab the HTML of each portfolio site and save
# it as dist/sites/<portfolio site name>.html
declare -A portfolioSites

portfolioSites[carona]='https%3A%2F%2Fwww.carona.com.au'
portfolioSites[enviroline]='https%3A%2F%2Fenviroline.net.au'
portfolioSites[magnattack]='https%3A%2F%2Fwww.magnattackglobal.com'
portfolioSites[pneutech]='https%3A%2F%2Fpneutech.com.au'
portfolioSites[tkvgroup]='https%3A%2F%2Fwww.tkv.com.au'
portfolioSites[veridia]='https%3A%2F%2Fveridia.com.au'

for siteName in "${!portfolioSites[@]}"; do
    siteUrl="${portfolioSites[$siteName]}"
    curl_and_save "/getPortfolioSite.php?siteName=$siteName&siteUrl=$siteUrl" "dist/sites/$siteName.html"
done
#endregion

#region Copying assets to dist
cp -R css dist/css
cp -R images dist/images
cp -R js dist/js
cp -R node_modules dist/node_modules
cp -R sites dist/sites
#endregion