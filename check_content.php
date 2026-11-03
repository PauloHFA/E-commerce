<?php
require '/var/www/html/wp-load.php';

$product = get_post(16);
echo 'Product content:' . PHP_EOL;
echo $product->post_content . PHP_EOL;
echo PHP_EOL . 'Product content filtered:' . PHP_EOL;
echo apply_filters('the_content', $product->post_content) . PHP_EOL;