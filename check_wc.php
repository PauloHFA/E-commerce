<?php
require '/var/www/html/wp-load.php';
echo 'WooCommerce active: ' . (class_exists('WooCommerce') ? 'yes' : 'no') . PHP_EOL;
echo 'WC() available: ' . (function_exists('WC') ? 'yes' : 'no') . PHP_EOL;
echo 'Product post type exists: ' . (post_type_exists('product') ? 'yes' : 'no') . PHP_EOL;
echo 'WC version: ' . (defined('WC_VERSION') ? WC_VERSION : 'not defined') . PHP_EOL;
echo 'Theme supports woocommerce: ' . (current_theme_supports('woocommerce') ? 'yes' : 'no') . PHP_EOL;