#!/bin/bash
set -euo pipefail

# Wait for database to be ready
echo "Waiting for database connection..."
until mysqladmin ping -h"$WORDPRESS_DB_HOST" -u"$WORDPRESS_DB_USER" -p"$WORDPRESS_DB_PASSWORD" --silent; do
    echo "Waiting for database..."
    sleep 2
done

echo "Database is ready!"

# Start Apache in background
apache2-foreground &
APACHE_PID=$!

# Wait for Apache to start
sleep 10

# Check if WordPress is installed
if ! wp core is-installed --allow-root 2>/dev/null; then
    echo "Installing WordPress..."
    
    # Download WordPress core
    wp core download --allow-root --force
    
    # Install WordPress
    wp core install \
        --url="http://localhost:8080" \
        --title="Bocek Development Site" \
        --admin_user="admin" \
        --admin_password="admin" \
        --admin_email="admin@example.com" \
        --allow-root
    
    echo "WordPress installed successfully!"
    
    # Install and configure plugins
    /usr/local/bin/install-plugins.sh
    
    echo "Setup completed!"
else
    echo "WordPress is already installed."
fi

# Keep Apache running
wait $APACHE_PID