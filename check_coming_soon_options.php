<?php
require '/var/www/html/wp-load.php';

echo 'woocommerce_coming_soon: ' . get_option('woocommerce_coming_soon') . PHP_EOL;
echo 'woocommerce_coming_soon_enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'woocommerce_store_pages_only: ' . get_option('woocommerce_store_pages_only') . PHP_EOL;
echo 'woocommerce_private_link: ' . get_option('woocommerce_private_link') . PHP_EOL;
echo 'woocommerce_share_key: ' . get_option('woocommerce_share_key') . PHP_EOL;

// Check if site is live
if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper')) {
    $helper = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper();
    $reflection = new ReflectionClass($helper);
    
    $method = $reflection->getMethod('is_site_live');
    $method->setAccessible(true);
    echo 'is_site_live: ' . ($method->invoke($helper) ? 'yes' : 'no') . PHP_EOL;
    
    $method2 = $reflection->getMethod('is_site_coming_soon');
    $method2->setAccessible(true);
    echo 'is_site_coming_soon: ' . ($method2->invoke($helper) ? 'yes' : 'no') . PHP_EOL;
    
    $method3 = $reflection->getMethod('is_store_coming_soon');
    $method3->setAccessible(true);
    echo 'is_store_coming_soon: ' . ($method3->invoke($helper) ? 'yes' : 'no') . PHP_EOL;
    
    $method4 = $reflection->getMethod('is_current_page_coming_soon');
    $method4->setAccessible(true);
    echo 'is_current_page_coming_soon: ' . ($method4->invoke($helper) ? 'yes' : 'no') . PHP_EOL;
}