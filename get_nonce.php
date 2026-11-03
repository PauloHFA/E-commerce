<?php
require '/var/www/html/wp-load.php';

// Get the nonce
$nonce = wp_create_nonce('moda_feminina_add_to_cart');
echo "Nonce: $nonce" . PHP_EOL;