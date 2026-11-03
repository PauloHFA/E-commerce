<?php
require '/var/www/html/wp-load.php';
global $wp_query;
echo 'Request: ' . $_SERVER['REQUEST_URI'] . PHP_EOL;
echo 'is_product: ' . (is_product() ? 'yes' : 'no') . PHP_EOL;
echo 'is_singular: ' . (is_singular('product') ? 'yes' : 'no') . PHP_EOL;
echo 'queried_object_id: ' . get_queried_object_id() . PHP_EOL;
echo 'queried_object: ';
var_dump(get_queried_object());
echo 'post_type: ' . get_post_type() . PHP_EOL;
echo 'is_page: ' . (is_page() ? 'yes' : 'no') . PHP_EOL;
echo 'is_single: ' . (is_single() ? 'yes' : 'no') . PHP_EOL;
echo 'is_singular: ' . (is_singular() ? 'yes' : 'no') . PHP_EOL;