<?php
/**
 * Product Card Template - Enhanced with quick-add, wishlist, badges
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product || !$product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$product_url = $product->get_permalink();
$product_image = $product->get_image('woocommerce_single', array('loading' => 'lazy'));
$product_title = $product->get_name();
$product_price = $product->get_price_html();
$product_categories = wc_get_product_category_list($product_id, ', ', '<span class="mf-product-category">', '</span>');
$is_on_sale = $product->is_on_sale();
$is_featured = $product->is_featured();
$is_out_of_stock = !$product->is_in_stock();

// Get attributes for quick add
$attributes = $product->get_attributes();
$has_variations = $product->is_type('variable');
$default_attributes = $product->get_default_attributes();

// Wishlist check
$wishlist = isset($_COOKIE['moda_feminina_wishlist']) ? explode(',', sanitize_text_field(wp_unslash($_COOKIE['moda_feminina_wishlist']))) : array();
$in_wishlist = in_array((string)$product_id, $wishlist);
?>

<li class="mf-product-card product type-product" data-id="<?php echo esc_attr($product_id); ?>" data-container="product-card">
    <article class="mf-product-card-inner">
        
        <!-- Product Image -->
        <div class="mf-product-card__image">
            <a href="<?php echo esc_url($product_url); ?>" class="mf-product-card__link" aria-label="<?php echo esc_attr($product_title); ?>">
                <?php echo $product_image; ?>
            </a>
            
            <!-- Badges -->
            <div class="mf-product-badges">
                <?php if ($is_on_sale) : ?>
                    <span class="mf-badge mf-badge--sale"><?php echo $product->get_sale_price_html(); ?></span>
                <?php endif; ?>
                
                <?php if ($is_featured) : ?>
                    <span class="mf-badge mf-badge--featured">Destaque</span>
                <?php endif; ?>
                
                <?php if ($is_out_of_stock) : ?>
                    <span class="mf-badge mf-badge--out-of-stock">Esgotado</span>
                <?php endif; ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="mf-product-quick-actions">
                <button type="button" class="mf-quick-action mf-wishlist-btn <?php echo $in_wishlist ? 'is-in-wishlist' : ''; ?>" 
                        data-product-id="<?php echo esc_attr($product_id); ?>"
                        aria-label="<?php echo $in_wishlist ? 'Remover da lista de desejos' : 'Adicionar à lista de desejos'; ?>">
                    <svg viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>
                
                <?php if (!$has_variations && $product->is_purchasable() && !$is_out_of_stock) : ?>
                    <button type="button" class="mf-quick-action mf-quick-add" 
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            aria-label="Adicionar <?php echo esc_attr($product_title); ?> ao carrinho">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1 0 8"></path>
                        </svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Content -->
        <div class="mf-product-card__content">
            <?php if ($product_categories) : ?>
                <div class="mf-product-card__category">
                    <?php echo $product_categories; ?>
                </div>
            <?php endif; ?>
            
            <h3 class="mf-product-card__title">
                <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_title); ?></a>
            </h3>
            
            <div class="mf-product-card__price">
                <?php echo $product_price; ?>
            </div>
            
            <!-- Quick Add for Simple Products -->
            <?php if (!$has_variations && $product->is_purchasable() && !$is_out_of_stock) : ?>
                <div class="mf-product-card__actions">
                    <form class="mf-quick-add-form" data-product-id="<?php echo esc_attr($product_id); ?>">
                        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">
                        <button type="submit" class="mf-btn mf-btn--primary mf-product-card__btn mf-product-card__btn--primary mf-add-to-cart" 
                                data-product-id="<?php echo esc_attr($product_id); ?>">
                            <span class="mf-btn-text">Adicionar</span>
                        </button>
                    </form>
                    <button type="button" class="mf-btn mf-btn--ghost mf-product-card__btn mf-product-card__btn--outline mf-wishlist-btn" 
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            aria-label="<?php echo $in_wishlist ? 'Remover da lista de desejos' : 'Adicionar à lista de desejos'; ?>">
                        <svg viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>
            <?php elseif ($has_variations) : ?>
                <div class="mf-product-card__actions">
                    <a href="<?php echo esc_url($product_url); ?>" class="mf-btn mf-btn--primary mf-product-card__btn mf-product-card__btn--primary">
                        <span class="mf-btn-text">Ver opções</span>
                    </a>
                    <button type="button" class="mf-btn mf-btn--ghost mf-product-card__btn mf-product-card__btn--outline mf-wishlist-btn" 
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            aria-label="<?php echo $in_wishlist ? 'Remover da lista de desejos' : 'Adicionar à lista de desejos'; ?>">
                        <svg viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>
            <?php elseif ($is_out_of_stock) : ?>
                <div class="mf-product-card__actions">
                    <button type="button" class="mf-btn mf-btn--secondary mf-product-card__btn mf-product-card__btn--primary" disabled>
                        <span class="mf-btn-text">Esgotado</span>
                    </button>
                    <button type="button" class="mf-btn mf-btn--ghost mf-product-card__btn mf-product-card__btn--outline mf-wishlist-btn" 
                            data-product-id="<?php echo esc_attr($product_id); ?>"
                            aria-label="<?php echo $in_wishlist ? 'Remover da lista de desejos' : 'Adicionar à lista de desejos'; ?>">
                        <svg viewBox="0 0 24 24" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </article>
</li>