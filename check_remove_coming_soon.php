<?php
require '/var/www/html/wp-load.php';

// Remove the Coming Soon handler from template_include
remove_action('template_include', array('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler', 'handle_template_include'), 10);

echo 'Coming Soon handler removed from template_include' . PHP_EOL;

// Now check template
$template = get_page_template();
echo 'Template after removing handler: ' . $template . PHP_EOL;

// Check if product template is found
$product = get_post(16);
$templates = array(
    'single-product.php',
    'single-product-' . $product->post_name . '.php',
    'single-' . $product->post_type . '.php',
    'single.php',
    'singular.php',
    'index.php',
);

echo PHP_EOL . 'Template hierarchy:' . PHP_EOL;
foreach ($templates as $template_name) {
    $located = locate_template($template_name);
    echo "  $template_name => " . ($located ? $located : 'NOT FOUND') . PHP_EOL;
}