<?php
require '/var/www/html/wp-load.php';

global $wp_rewrite;
$wp_rewrite->flush_rules();

echo 'Product permalink structure: ' . get_option('woocommerce_permalinks') . PHP_EOL;
echo 'Permalink structure: ' . get_option('permalink_structure') . PHP_EOL;
echo 'Product rewrite slug: ' . get_option('woocommerce_permalinks')['product_rewrite_slug'] . PHP_EOL;