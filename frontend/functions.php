<?php
/**
 * Moda Feminina — tema filho do Storefront.
 *
 * Escopo atual: visual e pequenos ajustes de vitrine.
 * Pagamento, frete, cupons, SEO e Instagram Shopping
 * dependem de plugins e credenciais do cliente (ver docs/status-requisitos.md).
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MODA_FEMININA_VERSION', '0.2.0');

function moda_feminina_enqueue_assets() {
    wp_enqueue_style(
        'moda-feminina-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Source+Sans+3:wght@400;600&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'moda-feminina-style',
        get_stylesheet_uri(),
        array('storefront-style', 'moda-feminina-fonts'),
        MODA_FEMININA_VERSION
    );

    wp_enqueue_script(
        'moda-feminina-script',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array('jquery'),
        MODA_FEMININA_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'moda_feminina_enqueue_assets');

function moda_feminina_setup() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'moda_feminina_setup');

function moda_feminina_cart_button_text() {
    return 'Adicionar à sacola';
}
add_filter('woocommerce_product_single_add_to_cart_text', 'moda_feminina_cart_button_text');
add_filter('woocommerce_product_add_to_cart_text', 'moda_feminina_cart_button_text');

function moda_feminina_featured_badge() {
    global $product;

    if ($product instanceof WC_Product && $product->is_featured()) {
        echo '<span class="new-badge">Nova coleção</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'moda_feminina_featured_badge', 9);
