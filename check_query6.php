<?php
require '/var/www/html/wp-load.php';

// Simulate the actual request
$_SERVER['REQUEST_URI'] = '/product/vestido-floral-midi/';
$_SERVER['REQUEST_METHOD'] = 'GET';

// Parse the request
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
echo 'template: ' . get_page_template() . PHP_EOL;
echo 'single_template: ' . get_single_template() . PHP_EOL;