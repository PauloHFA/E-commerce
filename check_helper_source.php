<?php
require '/var/www/html/wp-load.php';

// Check the Coming Soon helper source
$helper_class = 'Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper';
if (class_exists($helper_class)) {
    $reflection = new ReflectionClass($helper_class);
    echo 'Methods:' . PHP_EOL;
    foreach ($reflection->getMethods() as $method) {
        echo '  ' . $method->getName() . PHP_EOL;
    }
    
    // Check is_store_coming_soon
    $method = $reflection->getMethod('is_store_coming_soon');
    $method->setAccessible(true);
    $filename = $method->getFileName();
    $start = $method->getStartLine();
    $end = $method->getEndLine();
    
    echo PHP_EOL . "is_store_coming_soon method in $filename lines $start-$end:" . PHP_EOL;
    $lines = file($filename);
    for ($i = $start - 1; $i < $end; $i++) {
        echo ($i + 1) . ': ' . $lines[$i];
    }
    
    echo PHP_EOL . PHP_EOL . 'is_current_page_coming_soon method:' . PHP_EOL;
    $method2 = $reflection->getMethod('is_current_page_coming_soon');
    $start2 = $method2->getStartLine();
    $end2 = $method2->getEndLine();
    for ($i = $start2 - 1; $i < $end2; $i++) {
        echo ($i + 1) . ': ' . $lines[$i];
    }
}