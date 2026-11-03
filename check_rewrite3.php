<?php
require '/var/www/html/wp-load.php';

$wc_permalinks = get_option('woocommerce_permalinks');
echo 'WooCommerce permalinks: ' . print_r($wc_permalinks, true) . PHP_EOL;
echo 'Permalink structure: ' . get_option('permalink_structure') . PHP_EOL;