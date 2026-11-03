<?php
require '/var/www/html/wp-load.php';

global $wp_query;
$wp_query->parse_query(array(
    'post_type' => 'product',
    'p' => 16
));
$wp_query->query_posts();
$wp_query->register_globals();

echo 'is_product: ' . (is_product() ? 'yes' : 'no') . PHP_EOL;
echo 'is_singular: ' . (is_singular('product') ? 'yes' : 'no') . PHP_EOL;
echo 'queried_object_id: ' . get_queried_object_id() . PHP_EOL;
echo 'queried_object: ';
var_dump(get_queried_object());
echo 'template: ' . get_page_template() . PHP_EOL;
echo 'single_template: ' . get_single_template() . PHP_EOL;