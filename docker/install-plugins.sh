#!/bin/bash

# Wait for WordPress to be ready
until wp core is-installed --allow-root; do
    echo "Waiting for WordPress to be installed..."
    sleep 5
done

echo "WordPress is ready, installing plugins..."

# Read plugin configuration
PLUGINS_JSON="/var/www/html/plugins.json"

# Install plugins from WordPress repository
echo "Installing plugins from WordPress repository..."
wp plugin install advanced-custom-fields --activate --allow-root
wp plugin install polylang --activate --allow-root
wp plugin install loco-translate --activate --allow-root
wp plugin install duplicate-page --activate --allow-root
wp plugin install simple-history --activate --allow-root
wp plugin install automatic-translations-for-polylang --activate --allow-root

# Activate custom plugins that are mounted via volume
echo "Activating custom plugins..."
wp plugin activate custom/advanced-custom-fields-pro --allow-root
wp plugin activate custom/digitalmediate-wp-toolkit --allow-root
wp plugin activate custom/digitalmediate-zalohovani --allow-root

# Activate the custom theme
echo "Activating custom theme..."
wp theme activate bocek --allow-root

# Set permalink structure
echo "Setting permalink structure..."
wp rewrite structure '/%postname%/' --allow-root
wp rewrite flush --allow-root

echo "Plugin installation completed!"