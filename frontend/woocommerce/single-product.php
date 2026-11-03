<?php
/**
 * Single Product Template - Enhanced SPA-like experience
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure global $product is set for WooCommerce templates
global $product;
if (!is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_queried_object_id());
}

get_header();
?>

<div class="mf-single-product-wrapper">
    <div class="col-full">
        <main id="main-content" role="main">
        <div class="mf-product-main">
            
            <!-- Breadcrumb -->
            <nav class="mf-breadcrumb" aria-label="Breadcrumb">
                <?php
                woocommerce_breadcrumb(array(
                    'delimiter' => '<span class="mf-breadcrumb-sep" aria-hidden="true">/</span>',
                    'wrap_before' => '',
                    'wrap_after' => '',
                ));
                ?>
            </nav>

            <div class="mf-product-grid">
                
                <!-- Product Gallery -->
                <div class="mf-product-gallery" data-product-gallery>
                    <div class="mf-gallery-main">
                        <?php
                        woocommerce_show_product_images();
                        ?>
                    </div>
                    
                    <div class="mf-gallery-thumbs" data-gallery-thumbs>
                        <?php
                        global $product;
                        $attachment_ids = $product->get_gallery_image_ids();
                        if ($attachment_ids) {
                            foreach ($attachment_ids as $attachment_id) {
                                echo wp_get_attachment_image($attachment_id, 'woocommerce_thumbnail', false, array(
                                    'class' => 'mf-thumb',
                                    'loading' => 'lazy',
                                ));
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="mf-product-info" data-product-info>
                    
                    <!-- Category & Badges -->
                    <div class="mf-product-meta">
                        <?php
                        $categories = wc_get_product_category_list($product->get_id());
                        if ($categories) {
                            echo '<span class="mf-product-category">' . $categories . '</span>';
                        }
                        ?>
                        
                        <?php if ($product->is_on_sale()) : ?>
                            <span class="mf-badge mf-badge--sale"><?php echo esc_html($product->get_sale_price_html()); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($product->is_featured()) : ?>
                            <span class="mf-badge mf-badge--featured">Destaque</span>
                        <?php endif; ?>
                    </div>

                    <h1 class="mf-product-title"><?php the_title(); ?></h1>

                    <!-- Rating -->
                    <div class="mf-product-rating" data-rating>
                        <?php woocommerce_template_single_rating(); ?>
                    </div>

                    <!-- Price -->
                                        <div class="mf-product-price" data-price data-original-price="<?php echo $product->get_price_html(); ?>">
                        <?php woocommerce_template_single_price(); ?>
                    </div>

                                        <!-- Availability -->
                                        <div class="mf-product-availability" data-availability data-original-availability="<?php echo $product->is_type('variable') ? '' : $product->get_availability_html(); ?>">
                                            <?php if (!$product->is_type('variable')) : ?>
                                                <?php echo $product->get_availability_html(); ?>
                                            <?php endif; ?>
                                        </div>

                                        <!-- SKU -->
                                        <div class="mf-product-sku" data-sku data-original-sku="<?php echo $product->get_sku(); ?>">
                                            <?php if ($product->get_sku()) : ?>
                                                <span class="mf-sku-label">SKU:</span> <span class="mf-sku-value"><?php echo $product->get_sku(); ?></span>
                                            <?php endif; ?>
                                        </div>

                    <!-- Short Description -->
                    <div class="mf-product-excerpt">
                        <?php the_excerpt(); ?>
                    </div>

                    <!-- Color Swatches -->
                    <?php
                    $attributes = $product->get_attributes();
                    if (isset($attributes['pa_cor'])) :
                                            // Get available variations to filter attributes
                                            $available_variations = $product->get_available_variations();
                                            $available_colors = array();
                                            foreach ($available_variations as $variation) {
                                                if (isset($variation['attributes']['attribute_pa_cor'])) {
                                                    $available_colors[] = $variation['attributes']['attribute_pa_cor'];
                                                }
                                            }
                                            $available_colors = array_unique($available_colors);
                        
                                            $color_terms = wc_get_product_terms($product->get_id(), 'pa_cor', array('fields' => 'all'));
                                            if ($color_terms && !is_wp_error($color_terms)) :
                                                // Filter to only show colors with available variations
                                                $color_terms = array_filter($color_terms, function($term) use ($available_colors) {
                                                    return in_array($term->slug, $available_colors);
                                                });
                                                if ($color_terms) :
                                        ?>
                                        <fieldset class="mf-attribute-fieldset mf-color-swatches" data-attribute="color">
                                            <legend>Cor</legend>
                                            <div class="mf-swatch-group" role="radiogroup" aria-label="Selecione a cor">
                                                <?php foreach ($color_terms as $term) :
                                                    $hex = get_term_meta($term->term_id, 'mf_hex', true);
                                                    $image_id = get_term_meta($term->term_id, 'mf_image_id', true);
                                                ?>
                                                    <label class="mf-swatch-label" data-value="<?php echo esc_attr($term->slug); ?>">
                                                        <input type="radio" name="attribute_pa_cor" value="<?php echo esc_attr($term->slug); ?>" class="mf-swatch-input" aria-label="<?php echo esc_attr($term->name); ?>">
                                                        <span class="mf-swatch-visual" style="<?php echo $hex ? 'background-color:' . esc_attr($hex) : ''; ?>" 
                                                              <?php echo $image_id ? 'data-image="' . esc_url(wp_get_attachment_url($image_id)) . '"' : ''; ?>>>
                                                            <?php if ($image_id) : ?>
                                                                <img src="<?php echo esc_url(wp_get_attachment_url($image_id)); ?>" alt="" loading="lazy">
                                                            <?php endif; ?>
                                                        </span>
                                                        <span class="mf-swatch-name"><?php echo esc_html($term->name); ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </fieldset>
                                        <?php
                                                endif;
                                            endif;
                                        endif;
                                        ?>

                    <!-- Size Selector -->
                    <?php
                    if (isset($attributes['pa_tamanho'])) :
                                            // Get available variations to filter attributes
                                            $available_variations = $product->get_available_variations();
                                            $available_sizes = array();
                                            foreach ($available_variations as $variation) {
                                                if (isset($variation['attributes']['attribute_pa_tamanho'])) {
                                                    $available_sizes[] = $variation['attributes']['attribute_pa_tamanho'];
                                                }
                                            }
                                            $available_sizes = array_unique($available_sizes);
                        
                                            $size_terms = wc_get_product_terms($product->get_id(), 'pa_tamanho', array('fields' => 'all'));
                                            if ($size_terms && !is_wp_error($size_terms)) :
                                                // Filter to only show sizes with available variations
                                                $size_terms = array_filter($size_terms, function($term) use ($available_sizes) {
                                                    return in_array($term->slug, $available_sizes);
                                                });
                                                if ($size_terms) :
                                        ?>
                                        <fieldset class="mf-attribute-fieldset mf-size-selector" data-attribute="size">
                                            <legend>Tamanho</legend>
                                            <div class="mf-size-group" role="radiogroup" aria-label="Selecione o tamanho">
                                                <?php foreach ($size_terms as $term) :
                                                    $stock_status = get_term_meta($term->term_id, 'mf_stock_status', true);
                                                    $is_out_of_stock = $stock_status === 'outofstock';
                                                ?>
                                                    <label class="mf-size-label <?php echo $is_out_of_stock ? 'mf-size--unavailable' : ''; ?>" data-value="<?php echo esc_attr($term->slug); ?>">
                                                        <input type="radio" name="attribute_pa_tamanho" value="<?php echo esc_attr($term->slug); ?>" class="mf-size-input" 
                                                               <?php echo $is_out_of_stock ? 'disabled' : ''; ?> aria-label="<?php echo esc_attr($term->name); ?>">
                                                        <span class="mf-size-value"><?php echo esc_html($term->name); ?></span>
                                                        <?php if ($is_out_of_stock) : ?>
                                                            <span class="mf-size-stock">Indisponível</span>
                                                        <?php endif; ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                            <p class="mf-size-guide-link">
                                                <a href="#size-guide" class="mf-link">Guia de tamanhos</a>
                                            </p>
                                        </fieldset>
                                        <?php
                                                endif;
                                            endif;
                                        endif;
                                        ?>

                    <!-- Quantity & Add to Cart -->
                    <div class="mf-product-actions">
                                            <?php if ($product->is_type('variable')) : ?>
                                                <!-- WooCommerce Variable Product Add to Cart (Hidden Table, Custom UI) -->
                                                <?php
                                                $available_variations = $product->get_available_variations();
                                                $attributes = $product->get_variation_attributes();
                                                wc_get_template('single-product/add-to-cart/variable.php', array(
                                                    'available_variations' => $available_variations,
                                                    'attributes' => $attributes,
                                                    'product' => $product,
                                                ));
                                                ?>
                                                <style>
                                                    /* Hide WooCommerce default variations table - we use custom UI */
                                                    .variations_form .variations { display: none !important; }
                                                    .variations_form .reset_variations_alert { display: none !important; }
                                                    .variations_form .wc-product-gallery-default-template { display: none !important; }
                                                    .variations_form .single_variation_wrap .quantity { display: none !important; }
                                                    .variations_form .single_variation_wrap .single_add_to_cart_button { display: none !important; }
                                                    .variations_form .single_variation_wrap .variation_id { display: none !important; }
                                                </style>
                                                
                                                                    <!-- Custom Add to Cart for Variable Products -->
                                                                    <div class="mf-quantity-wrapper">
                                                                        <label for="quantity" class="screen-reader-text">Quantidade</label>
                                                                        <div class="mf-quantity" data-quantity>
                                                                            <button type="button" class="mf-qty-btn mf-qty-btn--minus" aria-label="Diminuir quantidade">−</button>
                                                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->get_max_purchase_quantity(); ?>" class="mf-qty-input" inputmode="numeric">
                                                                            <button type="button" class="mf-qty-btn mf-qty-btn--plus" aria-label="Aumentar quantidade">+</button>
                                                                        </div>
                                                                    </div>
                                                
                                                                    <button type="button" class="mf-btn mf-btn--primary mf-btn--lg mf-add-to-cart mf-add-to-cart-variable" 
                                                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                                            data-product-type="variable"
                                                                            disabled>
                                                                        <span class="mf-btn-text">Selecione as opções</span>
                                                                        <span class="mf-btn-loader" aria-hidden="true">
                                                                            <svg class="mf-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4 31.4" stroke-linecap="round"></circle></svg>
                                                                        </span>
                                                                    </button>
                                                                <?php else : ?>
                                                                    <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                                                                        <div class="mf-quantity-wrapper">
                                                                            <label for="quantity" class="screen-reader-text">Quantidade</label>
                                                                            <div class="mf-quantity" data-quantity>
                                                                                <button type="button" class="mf-qty-btn mf-qty-btn--minus" aria-label="Diminuir quantidade">−</button>
                                                                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product->get_max_purchase_quantity(); ?>" class="mf-qty-input" inputmode="numeric">
                                                                                <button type="button" class="mf-qty-btn mf-qty-btn--plus" aria-label="Aumentar quantidade">+</button>
                                                                            </div>
                                                                        </div>
                                
                                                                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" 
                                                                                class="mf-btn mf-btn--primary mf-btn--lg mf-add-to-cart" 
                                                                                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                                                data-product-type="<?php echo esc_attr($product->get_type()); ?>">
                                                                            <span class="mf-btn-text">Adicionar à sacola</span>
                                                                            <span class="mf-btn-loader" aria-hidden="true">
                                                                                <svg class="mf-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4 31.4" stroke-linecap="round"></circle></svg>
                                                                            </span>
                                                                        </button>
                                                                    </form>
                                                                <?php endif; ?>
                        
                                            <button type="button" class="mf-btn mf-btn--ghost mf-btn--lg mf-wishlist-btn" 
                                                    data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                    aria-label="Adicionar à lista de desejos">
                                                <svg class="mf-icon-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                </svg>
                                                <span class="mf-btn-text">Desejos</span>
                                            </button>
                                        </div>

                    <!-- Meta: SKU, Categories, Tags -->
                    <div class="mf-product-meta-bottom">
                                            <?php woocommerce_template_single_meta(); ?>
                    </div>

                                        <!-- Weight & Dimensions -->
                                        <?php if (!$product->is_type('variable') && ($product->has_weight() || $product->has_dimensions())) : ?>
                                            <div class="mf-product-weight" data-weight data-original-weight="<?php echo $product->get_weight_html(); ?>">
                                                <?php echo $product->get_weight_html(); ?>
                                                <?php echo $product->get_dimensions_html(); ?>
                                            </div>
                                        <?php endif; ?>

                    <!-- Share -->
                    <div class="mf-product-share">
                        <span class="mf-share-label">Compartilhar:</span>
                        <div class="mf-share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="mf-share-btn" aria-label="Compartilhar no Facebook">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="mf-share-btn" aria-label="Compartilhar no Twitter">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="mf-share-btn" aria-label="Compartilhar no Pinterest">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"></path></svg>
                            </a>
                            <button class="mf-share-btn mf-share-copy" aria-label="Copiar link">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Add to Cart (Mobile) -->
            <div class="mf-sticky-atc" data-sticky-atc hidden>
                <div class="mf-sticky-atc-inner">
                    <div class="mf-sticky-atc-image">
                        <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                    </div>
                    <div class="mf-sticky-atc-info">
                        <span class="mf-sticky-atc-title"><?php the_title(); ?></span>
                        <span class="mf-sticky-atc-price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                    <button type="button" class="mf-btn mf-btn--primary mf-sticky-atc-btn" 
                                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                            data-product-type="<?php echo esc_attr($product->get_type()); ?>"
                                            data-variation-id="">
                                            Adicionar
                                        </button>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mf-product-tabs" data-tabs>
            <nav class="mf-tabs-nav" role="tablist" aria-label="Informações do produto">
                <button role="tab" aria-selected="true" aria-controls="tab-description" id="tab-btn-description" class="mf-tab-btn is-active" data-tab="description">
                    Descrição
                </button>
                <button role="tab" aria-selected="false" aria-controls="tab-composition" id="tab-btn-composition" class="mf-tab-btn" data-tab="composition">
                    Composição e Cuidados
                </button>
                <button role="tab" aria-selected="false" aria-controls="tab-size-guide" id="tab-btn-size-guide" class="mf-tab-btn" data-tab="size-guide">
                    Guia de Tamanhos
                </button>
                <button role="tab" aria-selected="false" aria-controls="tab-reviews" id="tab-btn-reviews" class="mf-tab-btn" data-tab="reviews">
                    Avaliações (<span class="mf-review-count"><?php echo $product->get_review_count(); ?></span>)
                </button>
            </nav>

            <div class="mf-tabs-panels">
                <div role="tabpanel" id="tab-description" aria-labelledby="tab-btn-description" class="mf-tab-panel is-active">
                    <?php the_content(); ?>
                </div>

                <div role="tabpanel" id="tab-composition" aria-labelledby="tab-btn-composition" class="mf-tab-panel">
                    <?php
                    $composition = get_post_meta($product->get_id(), '_mf_composition', true);
                    $care = get_post_meta($product->get_id(), '_mf_care_instructions', true);
                    
                    if ($composition || $care) :
                        if ($composition) : ?>
                            <h2>Composição</h2>
                            <div class="mf-composition"><?php echo wp_kses_post($composition); ?></div>
                        <?php endif;
                        if ($care) : ?>
                            <h2>Cuidados</h2>
                            <div class="mf-care"><?php echo wp_kses_post($care); ?></div>
                        <?php endif;
                    else : ?>
                        <p class="mf-no-content">Informações de composição e cuidados serão adicionadas em breve.</p>
                    <?php endif; ?>
                </div>

                <div role="tabpanel" id="tab-size-guide" aria-labelledby="tab-btn-size-guide" class="mf-tab-panel">
                    <?php
                    $size_guide = get_post_meta($product->get_id(), '_mf_size_guide', true);
                    if ($size_guide) : ?>
                        <div class="mf-size-guide"><?php echo wp_kses_post($size_guide); ?></div>
                    <?php else : ?>
                        <div class="mf-size-guide-default">
                            <h2>Guia de Medidas</h2>
                            <table class="mf-size-table">
                                <thead>
                                    <tr>
                                        <th>Tamanho</th>
                                        <th>Busto (cm)</th>
                                        <th>Cintura (cm)</th>
                                        <th>Quadril (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>PP</td><td>80-84</td><td>62-66</td><td>88-92</td></tr>
                                    <tr><td>P</td><td>86-90</td><td>68-72</td><td>94-98</td></tr>
                                    <tr><td>M</td><td>92-96</td><td>74-78</td><td>100-104</td></tr>
                                    <tr><td>G</td><td>98-102</td><td>80-84</td><td>106-110</td></tr>
                                    <tr><td>GG</td><td>104-108</td><td>86-90</td><td>112-116</td></tr>
                                </tbody>
                            </table>
                            <p class="mf-size-note">* Medidas aproximadas do corpo. Consulte a tabela específica do produto quando disponível.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div role="tabpanel" id="tab-reviews" aria-labelledby="tab-btn-reviews" class="mf-tab-panel">
                    <?php comments_template(); ?>
                </div>
            </div>
        </div>

        <!-- Complete the Look / Cross-sells -->
        <?php
        $cross_sells = $product->get_cross_sell_ids();
        if ($cross_sells) : ?>
        <section class="mf-complete-look" aria-labelledby="complete-look-heading">
            <h2 id="complete-look-heading" class="mf-section-title">Complete o look</h2>
            <div class="mf-carousel" data-carousel="cross-sells">
                <div class="mf-carousel-track">
                    <?php
                    foreach ($cross_sells as $cross_sell_id) :
                        $cross_product = wc_get_product($cross_sell_id);
                        if ($cross_product && $cross_product->is_purchasable()) :
                            wc_get_template_part('content', 'product', array('product' => $cross_product));
                        endif;
                    endforeach;
                    ?>
                </div>
                <button class="mf-carousel-btn mf-carousel-btn--prev" aria-label="Produto anterior">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="mf-carousel-btn mf-carousel-btn--next" aria-label="Próximo produto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </section>
        <?php endif; ?>

        <!-- Recently Viewed -->
        <section class="mf-recently-viewed" aria-labelledby="recently-viewed-heading" hidden>
            <h2 id="recently-viewed-heading" class="mf-section-title">Vistos recentemente</h2>
            <div class="mf-carousel" data-carousel="recently-viewed">
                <div class="mf-carousel-track" data-recently-viewed-track></div>
                <button class="mf-carousel-btn mf-carousel-btn--prev" aria-label="Produto anterior">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="mf-carousel-btn mf-carousel-btn--next" aria-label="Próximo produto">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </section>
    </div>
    </main>
    </div>
    </div>

<?php get_footer(); ?>