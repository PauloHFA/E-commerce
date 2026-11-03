<?php
require '/var/www/html/wp-load.php';

// Check the Coming Soon handler source
$handler_class = 'Automattic\WooCommerce\Internal\ComingSoon\ComingSoonRequestHandler';
if (class_exists($handler_class)) {
    $reflection = new ReflectionClass($handler_class);
    $method = $reflection->getMethod('should_show_coming_soon');
    $method->setAccessible(true);
    
    // Get the source
    $filename = $method->getFileName();
    $start = $method->getStartLine();
    $end = $method->getEndLine();
    
    echo "should_show_coming_soon method in $filename lines $start-$end:" . PHP_EOL;
    $lines = file($filename);
    for ($i = $start - 1; $i < $end; $i++) {
        echo ($i + 1) . ': ' . $lines[$i];
    }
    
    echo PHP_EOL . PHP_EOL . 'handle_template_include method:' . PHP_EOL;
    $method2 = $reflection->getMethod('handle_template_include');
    $start2 = $method2->getStartLine();
    $end2 = $method2->getEndLine();
    for ($i = $start2 - 1; $i < $end2; $i++) {
        echo ($i + 1) . ': ' . $lines[$i];
    }
}