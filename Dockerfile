FROM php:8.4-apache

# Copy project files
COPY ./ /var/www/html/

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
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R a+rX /var/www/html \
    && a2enmod rewrite