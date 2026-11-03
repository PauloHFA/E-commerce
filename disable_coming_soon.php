<?php
require '/var/www/html/wp-load.php';

// Disable Coming Soon mode
update_option('woocommerce_coming_soon', 'no');
update_option('woocommerce_coming_soon_enabled', '');
update_option('woocommerce_store_pages_only', 'no');

echo 'Coming Soon mode disabled' . PHP_EOL;
echo 'woocommerce_coming_soon: ' . get_option('woocommerce_coming_soon') . PHP_EOL;
echo 'woocommerce_coming_soon_enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'woocommerce_store_pages_only: ' . get_option('woocommerce_store_pages_only') . PHP_EOL;