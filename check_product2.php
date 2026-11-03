<?php
require '/var/www/html/wp-load.php';

$product = get_post(16);
echo 'Product ID: ' . $product->ID . PHP_EOL;
echo 'Product post_type: ' . $product->post_type . PHP_EOL;
echo 'Product post_status: ' . $product->post_status . PHP_EOL;
echo 'Product permalink: ' . get_permalink($product->ID) . PHP_EOL;

// Check if it's a WC product
$wc_product = wc_get_product(16);
echo 'WC Product type: ' . $wc_product->get_type() . PHP_EOL;
echo 'WC Product is purchasable: ' . ($wc_product->is_purchasable() ? 'yes' : 'no') . PHP_EOL;

// Check rewrite rules for product
global $wp_rewrite;
$wp_rewrite->flush_rules();
echo 'Rewrite rules flushed' . PHP_EOL;