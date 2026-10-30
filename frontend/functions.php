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

define('MODA_FEMININA_VERSION', '0.3.0');

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

function moda_feminina_filter_url($changes) {
    $args = array();
    foreach (array('filter_tamanho', 'filter_cor', 'min_price', 'max_price', 'orderby') as $key) {
        if (isset($_GET[$key])) {
            $args[$key] = wc_clean(wp_unslash($_GET[$key]));
        }
    }

    foreach ($changes as $key => $value) {
        if ($value === null || $value === '') {
            unset($args[$key]);
        } else {
            $args[$key] = $value;
        }
    }

    if (is_product_category()) {
        $base = get_term_link(get_queried_object());
        if (is_wp_error($base)) {
            $base = wc_get_page_permalink('shop');
        }
    } else {
        $base = wc_get_page_permalink('shop');
    }

    return add_query_arg($args, $base);
}

function moda_feminina_shop_filters() {
    if (!is_shop() && !is_product_category()) {
        return;
    }

    $current_size = isset($_GET['filter_tamanho']) ? wc_clean(wp_unslash($_GET['filter_tamanho'])) : '';
    $current_color = isset($_GET['filter_cor']) ? wc_clean(wp_unslash($_GET['filter_cor'])) : '';
    $min_price = isset($_GET['min_price']) ? wc_clean(wp_unslash($_GET['min_price'])) : '';
    $max_price = isset($_GET['max_price']) ? wc_clean(wp_unslash($_GET['max_price'])) : '';
    $price_key = $min_price . '-' . $max_price;

    echo '<nav class="mf-filters" aria-label="Filtros de produtos">';
    echo '<a class="mf-clear" href="' . esc_url(moda_feminina_filter_url(array(
        'filter_tamanho' => null,
        'filter_cor' => null,
        'min_price' => null,
        'max_price' => null,
    ))) . '">Limpar</a>';

    echo '<div class="mf-filter-group"><span>Tamanho</span>';
    $sizes = get_terms(array('taxonomy' => 'pa_tamanho', 'hide_empty' => true));
    if (is_wp_error($sizes)) {
        $sizes = array();
    }
    foreach ($sizes as $term) {
        $active = $current_size === $term->slug;
        $url = moda_feminina_filter_url(array(
            'filter_tamanho' => $active ? null : $term->slug,
        ));
        echo '<a class="' . ($active ? 'is-active' : '') . '" href="' . esc_url($url) . '">' . esc_html($term->name) . '</a>';
    }
    echo '</div>';

    echo '<div class="mf-filter-group"><span>Cor</span>';
    $colors = get_terms(array('taxonomy' => 'pa_cor', 'hide_empty' => true));
    if (is_wp_error($colors)) {
        $colors = array();
    }
    foreach ($colors as $term) {
        $active = $current_color === $term->slug;
        $hex = get_term_meta($term->term_id, 'mf_hex', true);
        $url = moda_feminina_filter_url(array(
            'filter_cor' => $active ? null : $term->slug,
        ));
        echo '<a class="mf-swatch ' . ($active ? 'is-active' : '') . '" href="' . esc_url($url) . '" title="' . esc_attr($term->name) . '">';
        echo '<i style="background:' . esc_attr($hex ? $hex : '#ccc') . '"></i>' . esc_html($term->name);
        echo '</a>';
    }
    echo '</div>';

    $ranges = array(
        '0-220' => array('Até R$ 220', '0', '220'),
        '221-400' => array('R$ 221 a R$ 400', '221', '400'),
    );
    echo '<div class="mf-filter-group"><span>Preço</span>';
    foreach ($ranges as $key => $range) {
        $active = $price_key === $range[1] . '-' . $range[2];
        $url = moda_feminina_filter_url(array(
            'min_price' => $active ? null : $range[1],
            'max_price' => $active ? null : $range[2],
        ));
        echo '<a class="' . ($active ? 'is-active' : '') . '" href="' . esc_url($url) . '">' . esc_html($range[0]) . '</a>';
    }
    echo '</div></nav>';
}
add_action('woocommerce_before_shop_loop', 'moda_feminina_shop_filters', 15);
