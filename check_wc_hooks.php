<?php
require '/var/www/html/wp-load.php';

// Check if WooCommerce template loader is hooked
global $wp_filter;
echo 'template_include hooks:' . PHP_EOL;
if (isset($wp_filter['template_include'])) {
    foreach ($wp_filter['template_include']->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            $function = $callback['function'];
            if (is_array($function)) {
                echo "  Priority $priority: " . get_class($function[0]) . '::' . $function[1] . PHP_EOL;
            } elseif (is_string($function)) {
                echo "  Priority $priority: $function" . PHP_EOL;
            } else {
                echo "  Priority $priority: Closure" . PHP_EOL;
            }
        }
    }
}

echo PHP_EOL . 'template_redirect hooks:' . PHP_EOL;
if (isset($wp_filter['template_redirect'])) {
    foreach ($wp_filter['template_redirect']->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            $function = $callback['function'];
            if (is_array($function)) {
                echo "  Priority $priority: " . get_class($function[0]) . '::' . $function[1] . PHP_EOL;
            } elseif (is_string($function)) {
                echo "  Priority $priority: $function" . PHP_EOL;
            } else {
                echo "  Priority $priority: Closure" . PHP_EOL;
            }
        }
    }
}

// Check WC template loader
if (class_exists('WC_Template_Loader')) {
    $loader = new WC_Template_Loader();
    echo PHP_EOL . 'WC_Template_Loader instantiated' . PHP_EOL;
}