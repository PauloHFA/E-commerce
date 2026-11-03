<?php
require '/var/www/html/wp-load.php';

global $wp_rewrite;
$wp_rewrite->flush_rules();

$product = get_post(16);
echo 'Product ID: ' . $product->ID . PHP_EOL;
echo 'Product post_type: ' . $product->post_type . PHP_EOL;
echo 'Product post_name: ' . $product->post_name . PHP_EOL;
echo 'Product permalink: ' . get_permalink($product->ID) . PHP_EOL;

// Check if rewrite rules match
$request = 'product/vestido-floral-midi/';
$match = $wp_rewrite->match_request($request);
echo 'Match for ' . $request . ': ' . ($match ? 'yes' : 'no') . PHP_EOL;
if ($match) {
    echo 'Matched query: ' . print_r($match, true) . PHP_EOL;
}

// Check all rewrite rules
echo PHP_EOL . 'All rewrite rules:' . PHP_EOL;
foreach ($wp_rewrite->rules as $pattern => $query) {
    if (strpos($pattern, 'product') !== false || strpos($query, 'product') !== false) {
        echo "  $pattern => $query" . PHP_EOL;
    }
}