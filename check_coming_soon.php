<?php
require '/var/www/html/wp-load.php';

echo 'woocommerce_coming_soon_enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'woocommerce_coming_soon_page_id: ' . get_option('woocommerce_coming_soon_page_id') . PHP_EOL;

// Check if product is visible
$product = wc_get_product(16);
echo 'Product catalog_visibility: ' . $product->get_catalog_visibility() . PHP_EOL;
echo 'Product is_visible: ' . ($product->is_visible() ? 'yes' : 'no') . PHP_EOL;
echo 'Product is_purchasable: ' . ($product->is_purchasable() ? 'yes' : 'no') . PHP_EOL;