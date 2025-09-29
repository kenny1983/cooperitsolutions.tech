FROM php:8.4-apache

# Copy project files and cd into web root
COPY ./ /var/www/html/
RUN cd /var/www/html/

# Inline vhost conf with extensionless PHP URLs
RUN cat <<'EOF' > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted

        # Enable mod_rewrite
        RewriteEngine On

        # Redirect root to /home (extensionless)
        RewriteCond %{REQUEST_URI} ^/$
        RewriteRule ^/$ /home [R=302,L]

        # Serve /pages/<name> for /pages/<name>.php files
        RewriteCond %{DOCUMENT_ROOT}/pages/$1.php -f
        RewriteRule ^pages/([a-zA-Z0-9_-]+)$ /pages/$1.php [L]

        # Serve <name> for <name>.php files in DocumentRoot
        RewriteCond %{DOCUMENT_ROOT}/$1.php -f
        RewriteRule ^([a-zA-Z0-9_-]+)$ /$1.php [L]

        # Redirect requests that include .php to extensionless URL
        RewriteCond %{THE_REQUEST} \s([^.]+)\.php[\s?]
        RewriteRule ^ %1 [R=301,L]

        # PHP overrides
        <IfModule mod_php7.c>
            php_value display_errors 1
            php_value error_reporting E_ALL
        </IfModule>
    </Directory>

    DirectoryIndex index.php

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Ensure Apache user can read project files
RUN chown -R www-data:www-data . \
    && chmod -R a+rX . \
    && a2enmod rewrite

# Install Git and clean up package lists
# to keep the image as small as possible
RUN apt-get update \
    && apt-get install -y git \
    && rm -rf /var/lib/apt/lists/*

# Bypass Git ownership security bullshit
RUN git config --global --add safe.directory .

# Using our .dockerignore file, update the container's local
# repo to ignore each entry. This should prevent them from
# being removed from the remote repo accidentally
RUN while IFS= read -r file || [ -n "$file" ]; do \
    if git ls-files --error-unmatch "$file" > /dev/null 2>&1; then \
        git update-index --assume-unchanged "$file" && \
        echo "✅ Set assume-unchanged on $file"; \
    else \
        echo "⚠️ $file not tracked in git, skipping"; \
    fi; done < .dockerignore && rm .dockerignore

# Configure necessary locale-related settings
ENV LANG=C.utf8
ENV LC_ALL=C.utf8