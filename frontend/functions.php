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

define('MODA_FEMININA_VERSION', '1.0.0');

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
        array('jquery', 'wc-add-to-cart', 'jquery-ui-slider'),
        MODA_FEMININA_VERSION,
        true
    );

    // Localize script with AJAX parameters
    wp_localize_script('moda-feminina-script', 'mf_ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'add_to_cart_nonce' => wp_create_nonce('moda_feminina_add_to_cart'),
        'load_more_nonce' => wp_create_nonce('moda_feminina_load_more'),
        'wishlist_nonce' => wp_create_nonce('moda_feminina_wishlist'),
    ));
}
add_action('wp_enqueue_scripts', 'moda_feminina_enqueue_assets');

function moda_feminina_setup() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Custom image sizes
    add_image_size('mf-product-gallery', 800, 1000, true);
    add_image_size('mf-product-thumb', 150, 150, true);
    add_image_size('mf-collection', 600, 600, true);
    add_image_size('mf-instagram', 300, 300, true);
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
        echo '<span class="mf-badge mf-badge--featured">Nova coleção</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'moda_feminina_featured_badge', 9);

function moda_feminina_filter_url($changes) {
    $args = array();
    foreach (array('filter_tamanho', 'filter_cor', 'min_price', 'max_price', 'orderby', 'product_cat') as $key) {
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

// AJAX Add to Cart — suporta produtos simples e variáveis
function moda_feminina_ajax_add_to_cart() {
    check_ajax_referer('moda_feminina_add_to_cart', '_wpnonce');

    $product_id   = isset($_POST['product_id'])   ? absint($_POST['product_id'])   : 0;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $quantity     = isset($_POST['quantity'])      ? absint($_POST['quantity'])      : 1;

    // Attributes for variable products, e.g. {"attribute_pa_cor":"rosa","attribute_pa_tamanho":"m"}
    $variation = array();
    if (!empty($_POST['variation']) && is_array($_POST['variation'])) {
        foreach ($_POST['variation'] as $key => $val) {
            $clean_key = sanitize_text_field(wp_unslash($key));
            $clean_val = sanitize_text_field(wp_unslash($val));
            if (strpos($clean_key, 'attribute_') === 0) {
                $variation[$clean_key] = $clean_val;
            }
        }
    }

    if (!$product_id) {
        wp_send_json_error(array('message' => __('Produto inválido.', 'moda-feminina')));
        return;
    }

    $product = wc_get_product($product_id);
    if (!$product || !$product->is_purchasable()) {
        wp_send_json_error(array('message' => __('Produto não disponível.', 'moda-feminina')));
        return;
    }

    // For variable products the variation_id is required
    if ($product->is_type('variable') && !$variation_id) {
        wp_send_json_error(array('message' => __('Por favor, selecione as opções do produto.', 'moda-feminina')));
        return;
    }

    // Validate variation purchasable/in-stock before adding
    if ($variation_id) {
        $variation_obj = wc_get_product($variation_id);
        if (!$variation_obj || !$variation_obj->is_purchasable() || !$variation_obj->is_in_stock()) {
            wp_send_json_error(array('message' => __('Esta combinação está indisponível no momento.', 'moda-feminina')));
            return;
        }
    }

    $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

    if (false === $cart_item_key) {
        $notices = wc_get_notices('error');
        $msg     = !empty($notices) ? wp_strip_all_tags($notices[0]['notice']) : __('Erro ao adicionar ao carrinho.', 'moda-feminina');
        wc_clear_notices();
        wp_send_json_error(array('message' => $msg));
        return;
    }

    // Build mini-cart fragment the WooCommerce way
    ob_start();
    woocommerce_mini_cart();
    $mini_cart = ob_get_clean();

    $fragments = apply_filters(
        'woocommerce_add_to_cart_fragments',
        array(
            '.mf-mini-cart-drawer' => '<div class="mf-mini-cart-drawer widget_shopping_cart_content" id="mf-mini-cart-drawer" role="dialog" aria-label="Carrinho de compras" aria-hidden="true">' . $mini_cart . '</div>',
        )
    );

    // Use the actual product that was added (variation or parent)
    $display_product = $variation_id ? wc_get_product($variation_id) : $product;

    wp_send_json_success(array(
        'fragments'  => $fragments,
        'cart_hash'  => WC()->cart->get_cart_hash(),
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_total' => WC()->cart->get_cart_total(),
        'data'       => array(
            'name'  => $product->get_name(),
            'price' => $display_product->get_price_html(),
            'image' => $product->get_image('woocommerce_thumbnail'),
        ),
    ));
}
add_action('wp_ajax_moda_feminina_add_to_cart', 'moda_feminina_ajax_add_to_cart');
add_action('wp_ajax_nopriv_moda_feminina_add_to_cart', 'moda_feminina_ajax_add_to_cart');

// AJAX Load More Products
function moda_feminina_ajax_load_more() {
    check_ajax_referer('moda_feminina_load_more', '_wpnonce');
    
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 2;
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => wc_get_loop_prop('per_page', 12),
        'paged' => $paged,
        'meta_query' => WC()->query->get_meta_query(),
        'tax_query' => WC()->query->get_tax_query(),
    );
    
    // Apply current filters
    if (isset($_GET['filter_tamanho'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'pa_tamanho',
            'field' => 'slug',
            'terms' => wc_clean(wp_unslash($_GET['filter_tamanho'])),
        );
    }
    
    if (isset($_GET['filter_cor'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'pa_cor',
            'field' => 'slug',
            'terms' => wc_clean(wp_unslash($_GET['filter_cor'])),
        );
    }
    
    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array(wc_clean(wp_unslash($_GET['min_price'])), wc_clean(wp_unslash($_GET['max_price']))),
            'type' => 'numeric',
            'compare' => 'BETWEEN',
        );
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    if ($query->have_posts()) {
        woocommerce_product_loop_start();
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        woocommerce_product_loop_end();
    }
    $html = ob_get_clean();
    
    wp_reset_postdata();
    
    $max_pages = $query->max_num_pages;
    $next_page = $paged + 1;
    $permalink = $next_page <= $max_pages ? add_query_arg('paged', $next_page) : '';
    
    wp_send_json_success(array(
        'html' => $html,
        'max_page' => $max_pages,
        'current_page' => $paged,
        'permalink' => $permalink,
    ));
}
add_action('wp_ajax_moda_feminina_load_more_products', 'moda_feminina_ajax_load_more');
add_action('wp_ajax_nopriv_moda_feminina_load_more_products', 'moda_feminina_ajax_load_more');

// Cart Fragments for Mini Cart
function moda_feminina_cart_fragments($fragments) {
    // Mini-cart toggle button
    ob_start();
    ?>
    <div class="mf-mini-cart-toggle" data-cart-toggle>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1 0 8"></path>
        </svg>
        <span class="mf-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </div>
    <?php
    $fragments['.mf-mini-cart-toggle'] = ob_get_clean();
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'moda_feminina_cart_fragments');

// Wishlist Cookie Functions
function moda_feminina_get_wishlist() {
    if (isset($_COOKIE['moda_feminina_wishlist'])) {
        return array_map('absint', explode(',', sanitize_text_field(wp_unslash($_COOKIE['moda_feminina_wishlist']))));
    }
    return array();
}

function moda_feminina_set_wishlist($wishlist) {
    $wishlist = array_unique(array_map('absint', $wishlist));
    $value = implode(',', $wishlist);
    setcookie('moda_feminina_wishlist', $value, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
}

// AJAX Wishlist Toggle
function moda_feminina_ajax_toggle_wishlist() {
    check_ajax_referer('moda_feminina_wishlist', '_wpnonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error(array('message' => 'Produto inválido'));
    }
    
    $wishlist = moda_feminina_get_wishlist();
    $in_wishlist = in_array($product_id, $wishlist);
    
    if ($in_wishlist) {
        $wishlist = array_diff($wishlist, array($product_id));
        $action = 'removed';
    } else {
        $wishlist[] = $product_id;
        $action = 'added';
    }
    
    moda_feminina_set_wishlist($wishlist);
    
    wp_send_json_success(array(
        'action' => $action,
        'count' => count($wishlist),
    ));
}
add_action('wp_ajax_moda_feminina_toggle_wishlist', 'moda_feminina_ajax_toggle_wishlist');
add_action('wp_ajax_nopriv_moda_feminina_toggle_wishlist', 'moda_feminina_ajax_toggle_wishlist');

// Recently Viewed Products Cookie
function moda_feminina_track_recently_viewed($product_id) {
    $recently_viewed = array();
    
    if (isset($_COOKIE['moda_feminina_recently_viewed'])) {
        $recently_viewed = array_map('absint', explode(',', sanitize_text_field(wp_unslash($_COOKIE['moda_feminina_recently_viewed']))));
    }
    
    // Remove if already exists
    $recently_viewed = array_diff($recently_viewed, array($product_id));
    
    // Add to beginning
    array_unshift($recently_viewed, $product_id);
    
    // Keep only last 10
    $recently_viewed = array_slice($recently_viewed, 0, 10);
    
    $value = implode(',', $recently_viewed);
    setcookie('moda_feminina_recently_viewed', $value, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
}
add_action('template_redirect', function() {
    if (is_product()) {
        $queried_object = get_queried_object();
        if ($queried_object && isset($queried_object->ID)) {
            $product_id = absint($queried_object->ID);
            if ($product_id) {
                moda_feminina_track_recently_viewed($product_id);
            }
        }
    }
});

// Size Guide Meta Box
function moda_feminina_add_size_guide_meta() {
    add_meta_box(
        'mf_size_guide',
        'Guia de Tamanhos Personalizado',
        'moda_feminina_size_guide_callback',
        'product',
        'normal',
        'default'
    );
    
    add_meta_box(
        'mf_composition_care',
        'Composição e Cuidados',
        'moda_feminina_composition_care_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'moda_feminina_add_size_guide_meta');

function moda_feminina_size_guide_callback($post) {
    wp_nonce_field('moda_feminina_size_guide', 'mf_size_guide_nonce');
    $value = get_post_meta($post->ID, '_mf_size_guide', true);
    ?>
    <textarea name="_mf_size_guide" id="_mf_size_guide" rows="10" style="width: 100%;" class="mf-form-control"><?php echo esc_textarea($value); ?></textarea>
    <p class="description">HTML permitido: tabelas, listas, parágrafos. Use para guia de medidas específico do produto.</p>
    <?php
}

function moda_feminina_composition_care_callback($post) {
    wp_nonce_field('moda_feminina_composition_care', 'mf_composition_care_nonce');
    $composition = get_post_meta($post->ID, '_mf_composition', true);
    $care = get_post_meta($post->ID, '_mf_care_instructions', true);
    ?>
    <div class="mf-form-group">
        <label for="_mf_composition" class="mf-form-label">Composição</label>
        <textarea name="_mf_composition" id="_mf_composition" rows="5" style="width: 100%;" class="mf-form-control"><?php echo esc_textarea($composition); ?></textarea>
    </div>
    <div class="mf-form-group">
        <label for="_mf_care_instructions" class="mf-form-label">Instruções de Cuidado</label>
        <textarea name="_mf_care_instructions" id="_mf_care_instructions" rows="5" style="width: 100%;" class="mf-form-control"><?php echo esc_textarea($care); ?></textarea>
    </div>
    <?php
}

function moda_feminina_save_product_meta($post_id) {
    if (!isset($_POST['mf_size_guide_nonce']) || !wp_verify_nonce($_POST['mf_size_guide_nonce'], 'moda_feminina_size_guide')) {
        return;
    }
    
    if (!isset($_POST['mf_composition_care_nonce']) || !wp_verify_nonce($_POST['mf_composition_care_nonce'], 'moda_feminina_composition_care')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_product', $post_id)) {
        return;
    }
    
    if (isset($_POST['_mf_size_guide'])) {
        update_post_meta($post_id, '_mf_size_guide', wp_kses_post($_POST['_mf_size_guide']));
    }
    
    if (isset($_POST['_mf_composition'])) {
        update_post_meta($post_id, '_mf_composition', wp_kses_post($_POST['_mf_composition']));
    }
    
    if (isset($_POST['_mf_care_instructions'])) {
        update_post_meta($post_id, '_mf_care_instructions', wp_kses_post($_POST['_mf_care_instructions']));
    }
}
add_action('save_post_product', 'moda_feminina_save_product_meta');

// Add to cart redirect to cart page (optional - can be disabled for SPA feel)
add_filter('woocommerce_add_to_cart_redirect', function($url) {
    // Return false to prevent redirect (AJAX handles it)
    return false;
});

// Remove default WooCommerce styles we don't need
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Add body classes for styling
function moda_feminina_body_classes($classes) {
    if (is_product()) {
        $classes[] = 'mf-single-product';
    }
    if (is_shop() || is_product_category()) {
        $classes[] = 'mf-shop-page';
    }
    if (is_front_page()) {
        $classes[] = 'mf-home-page';
    }
    return $classes;
}
add_filter('body_class', 'moda_feminina_body_classes');

// AJAX Quick View - Load product data for modal
function moda_feminina_ajax_quick_view() {
    check_ajax_referer('moda_feminina_add_to_cart', '_wpnonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error(array('message' => 'Produto inválido'));
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => 'Produto não encontrado'));
    }
    
    ob_start();
    ?>
    <div class="mf-quick-view-gallery">
        <div class="mf-quick-view-gallery__main">
            <?php echo $product->get_image('mf-product-gallery'); ?>
        </div>
        <?php if ($product->get_gallery_image_ids()) : ?>
            <div class="mf-quick-view-gallery__thumbs">
                <?php foreach ($product->get_gallery_image_ids() as $image_id) : ?>
                    <button class="mf-quick-view-gallery__thumb" type="button" aria-label="Ver imagem">
                        <?php echo wp_get_attachment_image($image_id, 'mf-product-thumb'); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mf-quick-view-info">
        <h2 class="mf-quick-view-info__title"><?php echo esc_html($product->get_name()); ?></h2>
        
        <div class="mf-quick-view-info__price">
            <?php echo $product->get_price_html(); ?>
        </div>
        
        <?php if ($product->is_type('variable')) : ?>
            <div class="mf-quick-view-info__attributes">
                <?php
                $attributes = $product->get_variation_attributes();
                foreach ($attributes as $attr_name => $options) :
                    $taxonomy = wc_attribute_taxonomy_name($attr_name);
                    $tax = get_taxonomy($taxonomy);
                    $label = $tax ? $tax->labels->singular_name : ucfirst(str_replace('pa_', '', $attr_name));
                ?>
                    <div class="mf-quick-view-info__attribute" data-attribute="<?php echo esc_attr($attr_name); ?>">
                        <span class="mf-quick-view-info__attribute-label"><?php echo esc_html($label); ?></span>
                        <div class="mf-quick-view-info__attribute-options">
                            <?php foreach ($options as $option) : ?>
                                <button type="button" 
                                        class="mf-quick-view-info__attribute-option"
                                        data-attribute-name="<?php echo esc_attr($attr_name); ?>"
                                        data-attribute-value="<?php echo esc_attr($option); ?>">
                                    <?php echo esc_html($option); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="mf-quick-view-info__quantity">
            <label class="mf-quick-view-info__quantity-label" for="mf-qv-quantity">Quantidade</label>
            <div class="mf-quick-view-info__quantity-input">
                <button type="button" class="mf-quick-view-info__quantity-btn mf-qty-btn--minus" aria-label="Diminuir">−</button>
                <input type="number" 
                       id="mf-qv-quantity" 
                       class="mf-quick-view-info__quantity-value" 
                       value="1" 
                       min="1" 
                       max="<?php echo $product->get_stock_quantity() ? $product->get_stock_quantity() : 99; ?>"
                       aria-label="Quantidade">
                <button type="button" class="mf-quick-view-info__quantity-btn mf-qty-btn--plus" aria-label="Aumentar">+</button>
            </div>
        </div>
        
        <button type="button" 
                class="mf-quick-view-info__btn mf-quick-view-info__btn--add-to-cart"
                data-product-id="<?php echo esc_attr($product_id); ?>"
                data-variation-id="0">
            Adicionar à sacola
        </button>
        
        <?php if ($product->get_short_description()) : ?>
            <div class="mf-quick-view-info__excerpt">
                <?php echo wc_format_content($product->get_short_description()); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    
    $html = ob_get_clean();
    
    wp_send_json_success(array(
        'html' => $html,
    ));
}
add_action('wp_ajax_moda_feminina_quick_view', 'moda_feminina_ajax_quick_view');
add_action('wp_ajax_nopriv_moda_feminina_quick_view', 'moda_feminina_ajax_quick_view');

// AJAX Find Matching Variation
function moda_feminina_ajax_find_variation() {
    check_ajax_referer('moda_feminina_add_to_cart', '_wpnonce');
    
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $variation = isset($_POST['variation']) && is_array($_POST['variation']) ? $_POST['variation'] : array();
    
    if (!$product_id) {
        wp_send_json_error(array('message' => 'Produto inválido'));
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product || !$product->is_type('variable')) {
        wp_send_json_error(array('message' => 'Produto não é variável'));
    }
    
    // Find matching variation using data store (replaces deprecated get_matching_variation)
    $data_store = WC_Data_Store::load('product');
    $variation_id = $data_store->find_matching_product_variation($product, $variation);
    
    if ($variation_id && is_numeric($variation_id) && $variation_id > 0) {
        $variation_obj = wc_get_product($variation_id);
        
        $response = array(
            'variation_id' => $variation_id,
            'price_html' => $variation_obj->get_price_html(),
            'in_stock' => $variation_obj->is_in_stock(),
            'stock_quantity' => $variation_obj->get_stock_quantity(),
        );
        
        // Get variation image
        $image_id = $variation_obj->get_image_id();
        if ($image_id) {
            $response['image'] = wp_get_attachment_image_url($image_id, 'mf-product-gallery');
        }
        
        wp_send_json_success(array('data' => $response));
    } else {
        wp_send_json_error(array('message' => 'Combinação não encontrada'));
    }
}
add_action('wp_ajax_moda_feminina_find_variation', 'moda_feminina_ajax_find_variation');
add_action('wp_ajax_nopriv_moda_feminina_find_variation', 'moda_feminina_ajax_find_variation');
