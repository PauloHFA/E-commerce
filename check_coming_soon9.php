<?php
require '/var/www/html/wp-load.php';

// Check Coming Soon handler
echo 'Coming Soon enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'Coming Soon page ID: ' . get_option('woocommerce_coming_soon_page_id') . PHP_EOL;

// Check if coming soon is enabled via WC
if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler')) {
    $handler = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler();
    echo PHP_EOL . 'ComingSoonRequestHandler instantiated' . PHP_EOL;
    
    // Check should_show_coming_soon directly
    $reflection = new ReflectionClass($handler);
    $method = $reflection->getMethod('should_show_coming_soon');
    $method->setAccessible(true);
    
    // Try to call it - it might fail but let's see
    try {
        $result = $method->invoke($handler);
        echo 'should_show_coming_soon: ' . ($result ? 'yes' : 'no') . PHP_EOL;
    } catch (Exception $e) {
        echo 'should_show_coming_soon error: ' . $e->getMessage() . PHP_EOL;
    }
    
    // Check the helper
    if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper')) {
        $helper = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper();
        echo 'ComingSoonHelper instantiated' . PHP_EOL;
        
        $reflection2 = new ReflectionClass($helper);
        $method2 = $reflection2->getMethod('is_store_coming_soon');
        $method2->setAccessible(true);
        echo 'is_store_coming_soon: ' . ($method2->invoke($helper) ? 'yes' : 'no') . PHP_EOL;
    }
}