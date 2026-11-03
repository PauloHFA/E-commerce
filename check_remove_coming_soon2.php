<?php
require '/var/www/html/wp-load.php';

// Remove the Coming Soon handler from template_include
remove_action('template_include', array('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler', 'handle_template_include'), 10);

echo 'Coming Soon handler removed from template_include' . PHP_EOL;

// Also remove from template_redirect if present
remove_action('template_redirect', array('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler', 'handle_template_include'), 10);

// Now simulate the request
$_SERVER['REQUEST_URI'] = '/product/vestido-floral-midi/';
$_SERVER['REQUEST_METHOD'] = 'GET';

$wp = new WP();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();

global $wp_query;
echo 'is_product: ' . (is_product() ? 'yes' : 'no') . PHP_EOL;
echo 'is_singular: ' . (is_singular('product') ? 'yes' : 'no') . PHP_EOL;
echo 'queried_object_id: ' . get_queried_object_id() . PHP_EOL;
echo 'queried_object: ';
var_dump(get_queried_object());

// Check template
$template = get_page_template();
echo PHP_EOL . 'Template: ' . $template . PHP_EOL;

// Check single template
$single_template = get_single_template();
echo 'Single template: ' . $single_template . PHP_EOL;