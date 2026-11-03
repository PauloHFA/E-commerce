<?php
require '/var/www/html/wp-load.php';

// Check WooCommerce template loading
global $woocommerce;
echo 'WooCommerce version: ' . WC_VERSION . PHP_EOL;

// Check template path
$template_path = wc_locate_template('single-product.php');
echo 'wc_locate_template single-product.php: ' . $template_path . PHP_EOL;

// Check theme support
echo 'Theme supports woocommerce: ' . (current_theme_supports('woocommerce') ? 'yes' : 'no') . PHP_EOL;

// Check if our template is in the right place
$theme_template = get_stylesheet_directory() . '/woocommerce/single-product.php';
echo 'Theme template exists: ' . (file_exists($theme_template) ? 'yes' : 'no') . PHP_EOL;
echo 'Theme template path: ' . $theme_template . PHP_EOL;

// Check parent theme template
$parent_template = get_template_directory() . '/woocommerce/single-product.php';
echo 'Parent template exists: ' . (file_exists($parent_template) ? 'yes' : 'no') . PHP_EOL;
echo 'Parent template path: ' . $parent_template . PHP_EOL;

// Check plugin template
$plugin_template = WC()->plugin_path() . '/templates/single-product.php';
echo 'Plugin template exists: ' . (file_exists($plugin_template) ? 'yes' : 'no') . PHP_EOL;
echo 'Plugin template path: ' . $plugin_template . PHP_EOL;

// Check template hierarchy filter
add_filter('wc_get_template', function($template, $template_name, $args) {
    echo "wc_get_template called: $template_name => $template" . PHP_EOL;
    return $template;
}, 10, 3);

// Now call wc_get_template
$template = wc_get_template('single-product.php');
echo 'wc_get_template single-product.php returned: ' . gettype($template) . PHP_EOL;