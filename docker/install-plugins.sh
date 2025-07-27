#!/bin/bash

# Wait for WordPress to be ready
until wp core is-installed --allow-root; do
    echo "Waiting for WordPress to be installed..."
    sleep 5
done

echo "WordPress is ready, installing plugins..."

# Install plugins from WordPress repository (with error handling)
echo "Installing plugins from WordPress repository..."
wp plugin install advanced-custom-fields --activate --allow-root || echo "Failed to install ACF free"
wp plugin install polylang --activate --allow-root || echo "Failed to install Polylang"
wp plugin install loco-translate --activate --allow-root || echo "Failed to install Loco Translate"
wp plugin install duplicate-page --activate --allow-root || echo "Failed to install Duplicate Page"
wp plugin install simple-history --activate --allow-root || echo "Failed to install Simple History"
wp plugin install automatic-translations-for-polylang --activate --allow-root || echo "Failed to install Automatic Translations"

# Wait a bit for plugins to settle
sleep 2

# Activate custom plugins that are mounted via volume
echo "Activating custom plugins..."
wp plugin activate advanced-custom-fields-pro --allow-root || echo "Failed to activate ACF Pro"
wp plugin activate digitalmediate-wp-toolkit --allow-root || echo "Failed to activate DM Toolkit"
wp plugin activate digitalmediate-zalohovani --allow-root || echo "Failed to activate DM Backup"

# Activate the custom theme
echo "Activating custom theme..."
wp theme activate bocek --allow-root || echo "Failed to activate theme"

# Set permalink structure
echo "Setting permalink structure..."
wp rewrite structure '/%postname%/' --allow-root || echo "Failed to set permalinks"
wp rewrite flush --allow-root || echo "Failed to flush rewrites"

echo "Plugin installation completed!"