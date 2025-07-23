FROM wordpress:6.4-php8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Install WP-CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/wp-cli/v2.8.1/utils/wp-completion.bash \
    && curl -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/wp-cli/v2.8.1/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

# Copy WordPress configuration
COPY wp-config-docker.php /var/www/html/wp-config.php

# Copy custom theme
COPY bocek /var/www/html/wp-content/themes/bocek

# Custom plugins will be mounted via volume mapping from ./plugins/
# No need to copy them during build

# Copy plugin installation script
COPY docker/install-plugins.sh /usr/local/bin/install-plugins.sh
RUN chmod +x /usr/local/bin/install-plugins.sh

# Copy plugin configuration
COPY docker/plugins.json /var/www/html/plugins.json

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create uploads directory
RUN mkdir -p /var/www/html/wp-content/uploads
RUN chown -R www-data:www-data /var/www/html/wp-content/uploads

# Copy entrypoint script
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]