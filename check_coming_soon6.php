<?php
require '/var/www/html/wp-load.php';

// Check Coming Soon handler
echo 'Coming Soon enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'Coming Soon page ID: ' . get_option('woocommerce_coming_soon_page_id') . PHP_EOL;

// Check if coming soon is enabled via WC
if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler')) {
    $handler = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler();
    echo PHP_EOL . 'ComingSoonRequestHandler instantiated' . PHP_EOL;
    
    // Check should_show_coming_soon
    $reflection = new ReflectionClass($handler);
    $method = $reflection->getMethod('should_show_coming_soon');
    $method->setAccessible(true);
    echo 'should_show_coming_soon: ' . ($method->invoke($handler) ? 'yes' : 'no') . PHP_EOL;
    
    // Check init
    $method2 = $reflection->getMethod('init');
    $method2->setAccessible(true);
    $method2->invoke($handler);
    echo 'init called' . PHP_EOL;
    
    // Check again
    echo 'should_show_coming_soon after init: ' . ($method->invoke($handler) ? 'yes' : 'no') . PHP_EOL;
}