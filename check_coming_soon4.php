<?php
require '/var/www/html/wp-load.php';

// Check Coming Soon handler
echo 'Coming Soon enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'Coming Soon page ID: ' . get_option('woocommerce_coming_soon_page_id') . PHP_EOL;

// Check if coming soon is enabled via WC
if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler')) {
    $handler = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler();
    echo PHP_EOL . 'ComingSoonRequestHandler instantiated' . PHP_EOL;
    
    // Check the method
    $reflection = new ReflectionClass($handler);
    $method = $reflection->getMethod('is_coming_soon_enabled');
    $method->setAccessible(true);
    echo 'is_coming_soon_enabled method result: ' . ($method->invoke($handler) ? 'yes' : 'no') . PHP_EOL;
    
    $method2 = $reflection->getMethod('is_coming_soon_page');
    $method2->setAccessible(true);
    echo 'is_coming_soon_page method result: ' . ($method2->invoke($handler) ? 'yes' : 'no') . PHP_EOL;
    
    // Check handle_template_include
    $method3 = $reflection->getMethod('handle_template_include');
    $method3->setAccessible(true);
    
    // Get the current template
    $template = get_page_template();
    echo 'Current template before handler: ' . $template . PHP_EOL;
    
    // Call the handler
    $result = $method3->invoke($handler, array($template));
    echo 'Template after handler: ' . $result . PHP_EOL;
}