<?php
require '/var/www/html/wp-load.php';

// Check template hierarchy for single product
$product = get_post(16);
echo 'Product ID: ' . $product->ID . PHP_EOL;
echo 'Product post_type: ' . $product->post_type . PHP_EOL;

// Check template hierarchy
$templates = array(
    'single-product.php',
    'single-product-' . $product->post_name . '.php',
    'single-' . $product->post_type . '.php',
    'single.php',
    'singular.php',
    'index.php',
);

echo PHP_EOL . 'Template hierarchy:' . PHP_EOL;
foreach ($templates as $template) {
    $located = locate_template($template);
    echo "  $template => " . ($located ? $located : 'NOT FOUND') . PHP_EOL;
}

// Check if WooCommerce template is being used
echo PHP_EOL . 'WooCommerce template:' . PHP_EOL;
$wc_template = wc_get_template('single-product.php');
echo 'wc_get_template single-product.php: ' . $wc_template . PHP_EOL;

// Check theme file path
echo PHP_EOL . 'Theme paths:' . PHP_EOL;
echo 'get_stylesheet_directory: ' . get_stylesheet_directory() . PHP_EOL;
echo 'get_template_directory: ' . get_template_directory() . PHP_EOL;
echo 'get_stylesheet_directory_uri: ' . get_stylesheet_directory_uri() . PHP_EOL;
echo 'get_template_directory_uri: ' . get_template_directory_uri() . PHP_EOL;

// Check if our custom template exists
$custom_template = get_stylesheet_directory() . '/woocommerce/single-product.php';
echo PHP_EOL . 'Custom template exists: ' . (file_exists($custom_template) ? 'yes' : 'no') . PHP_EOL;
echo 'Custom template path: ' . $custom_template . PHP_EOL;