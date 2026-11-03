<?php
require '/var/www/html/wp-load.php';

$product = wc_get_product(16);
echo 'Product ID: ' . $product->get_id() . PHP_EOL;
echo 'Product Name: ' . $product->get_name() . PHP_EOL;
echo 'Product Type: ' . $product->get_type() . PHP_EOL;
echo 'Is Purchasable: ' . ($product->is_purchasable() ? 'yes' : 'no') . PHP_EOL;
echo 'Is In Stock: ' . ($product->is_in_stock() ? 'yes' : 'no') . PHP_EOL;
echo 'Price: ' . $product->get_price_html() . PHP_EOL;
if ($product->is_type('variable')) {
    echo 'Variations:' . PHP_EOL;
    $variations = $product->get_available_variations();
    foreach ($variations as $v) {
        echo '  - Variation ID: ' . $v['variation_id'] . ', Attributes: ' . json_encode($v['attributes']) . ', Price: ' . $v['display_price'] . ', In Stock: ' . ($v['is_in_stock'] ? 'yes' : 'no') . PHP_EOL;
    }
}