<?php
require '/var/www/html/wp-load.php';

// Check Coming Soon handler
echo 'Coming Soon enabled: ' . get_option('woocommerce_coming_soon_enabled') . PHP_EOL;
echo 'Coming Soon page ID: ' . get_option('woocommerce_coming_soon_page_id') . PHP_EOL;

// Check if the handler is active
global $wp_filter;
if (isset($wp_filter['template_include'])) {
    foreach ($wp_filter['template_include']->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            $function = $callback['function'];
            if (is_array($function) && isset($function[0])) {
                $class = get_class($function[0]);
                if (strpos($class, 'ComingSoon') !== false) {
                    echo "Found ComingSoon handler at priority $priority: $class::{$function[1]}" . PHP_EOL;
                }
            }
        }
    }
}

// Check if product is visible
$product = wc_get_product(16);
echo PHP_EOL . 'Product catalog_visibility: ' . $product->get_catalog_visibility() . PHP_EOL;
echo 'Product is_visible: ' . ($product->is_visible() ? 'yes' : 'no') . PHP_EOL;
echo 'Product is_purchasable: ' . ($product->is_purchasable() ? 'yes' : 'no') . PHP_EOL;
echo 'Product status: ' . $product->get_status() . PHP_EOL;

// Check if coming soon is enabled via WC
if (class_exists('Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler')) {
    $handler = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler();
    echo PHP_EOL . 'ComingSoonRequestHandler instantiated' . PHP_EOL;
    echo 'Is coming soon enabled: ' . ($handler->is_coming_soon_enabled() ? 'yes' : 'no') . PHP_EOL;
    echo 'Is coming soon page: ' . ($handler->is_coming_soon_page() ? 'yes' : 'no') . PHP_EOL;
}