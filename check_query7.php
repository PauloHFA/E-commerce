<?php
require '/var/www/html/wp-load.php';

// Check if product post type is registered
echo 'Product post type exists: ' . (post_type_exists('product') ? 'yes' : 'no') . PHP_EOL;
echo 'Product post type object: ';
var_dump(get_post_type_object('product'));

// Check if the rewrite rules are being used
global $wp_rewrite;
echo PHP_EOL . 'Rewrite rules count: ' . count($wp_rewrite->rules) . PHP_EOL;

// Check if the request is being parsed correctly
$request = 'product/vestido-floral-midi/';
$wp_rewrite->flush_rules();

// Try to match the request
$matched = false;
foreach ($wp_rewrite->rules as $pattern => $query) {
    if (preg_match('#^' . $pattern . '#', $request)) {
        echo "Matched: $pattern => $query" . PHP_EOL;
        $matched = true;
    }
}
if (!$matched) {
    echo 'No rewrite rule matched for: ' . $request . PHP_EOL;
}