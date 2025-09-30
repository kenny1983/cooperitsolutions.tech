FROM php:8.4-apache

# Copy project files to container's web root and cd into it
COPY ./ /var/www/html/
RUN cd /var/www/html/

# Add an inline Apache vhost conf with extensionless PHP URLs
RUN cat <<'EOF' > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:8080>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/html

	<Directory /var/www/html>
		Options Indexes FollowSymLinks
		AllowOverride None
		Require all granted

		# Enable mod_rewrite
		RewriteEngine On

		# Redirect any request to /pages/<page name>.php → /<page name> (external redirect)
		RewriteCond %{THE_REQUEST} \s/pages/([a-zA-Z0-9_-]+)\.php[\s?] [NC]
		RewriteRule ^ /%1 [R=301,L]

		# Internal rewrite: map /<page name> → /pages/<page name>.php
		RewriteCond %{DOCUMENT_ROOT}/pages/$1.php -f
		RewriteRule ^([a-zA-Z0-9_-]+)$ /pages/$1.php [L]

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

# Set Apache to listen on port 8080 instead of 80
RUN sed -i 's/^Listen 80$/Listen 8080/' /etc/apache2/ports.conf

# Set Apache's ServerName so that it
# stops complaining about not having one!
RUN echo 'ServerName dev.cooperitsolutions.tech' >> /etc/apache2/apache2.conf

# Ensure that the Apache user can read project files
RUN chown -R www-data:www-data . \
	&& chmod -R a+rX . \
	&& a2enmod rewrite

# Install Git and clean up package lists
# to keep the image as small as possible
RUN apt-get update \
	&& apt-get install -y git \
	&& rm -rf /var/lib/apt/lists/*

# Configure necessary locale-related settings
ENV LANG=C.utf8
ENV LC_ALL=C.utf8

# Install and setup XDebug
RUN pecl install xdebug \
	&& docker-php-ext-enable xdebug \
	&& { \
		echo "xdebug.mode=debug"; \
		echo "xdebug.start_with_request=trigger"; \
		echo "xdebug.client_host=host.docker.internal"; \
		echo "xdebug.client_port=9003"; \
		echo "xdebug.log_level=0"; \
		echo "xdebug.collect_params=0"; \
	} > /usr/local/etc/php/conf.d/xdebug.ini

# Ensure that our init script is sourced in every interactive shell
RUN echo "source /var/www/html/init-container.sh" >> /root/.bashrc