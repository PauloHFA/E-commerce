<?php
require '/var/www/html/wp-load.php';

global $wp_rewrite;
echo 'Rewrite rules count: ' . count($wp_rewrite->rules) . PHP_EOL;
echo 'Product rewrite rules:' . PHP_EOL;
foreach ($wp_rewrite->rules as $pattern => $query) {
    if (strpos($pattern, 'product') !== false || strpos($query, 'product') !== false) {
        echo "  $pattern => $query" . PHP_EOL;
    }
}

echo PHP_EOL . 'Product permalink structure: ' . get_option('woocommerce_permalinks') . PHP_EOL;
echo 'Permalink structure: ' . get_option('permalink_structure') . PHP_EOL;