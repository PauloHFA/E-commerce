<?php
require '/var/www/html/wp-load.php';

global $wp_rewrite;
$wp_rewrite->flush_rules();

$product = get_post(16);
echo 'Product ID: ' . $product->ID . PHP_EOL;
echo 'Product post_type: ' . $product->post_type . PHP_EOL;
echo 'Product post_name: ' . $product->post_name . PHP_EOL;
echo 'Product permalink: ' . get_permalink($product->ID) . PHP_EOL;

// Check all rewrite rules
echo PHP_EOL . 'All rewrite rules:' . PHP_EOL;
foreach ($wp_rewrite->rules as $pattern => $query) {
    if (strpos($pattern, 'product') !== false || strpos($query, 'product') !== false) {
        echo "  $pattern => $query" . PHP_EOL;
    }
}