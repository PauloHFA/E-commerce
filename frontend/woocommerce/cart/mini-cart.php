<?php
/**
 * Mini Cart Drawer Template
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

$cart_items = WC()->cart->get_cart();
$cart_count = WC()->cart->get_cart_contents_count();
$cart_total = WC()->cart->get_cart_total();
?>

<div class="mf-mini-cart-drawer" id="mf-mini-cart-drawer" role="dialog" aria-label="Carrinho de compras" aria-hidden="true">
    <div class="mf-mini-cart-drawer__header">
        <h2 class="mf-mini-cart-drawer__title">Sua Sacola</h2>
        <button type="button" class="mf-mini-cart-drawer__close" aria-label="Fechar carrinho">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    
    <div class="mf-mini-cart-drawer__body">
        <?php if ($cart_count > 0) : ?>
            <div class="mf-cart-items">
                <?php foreach ($cart_items as $cart_item_key => $cart_item) :
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                    $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $product_image = $_product->get_image('woocommerce_thumbnail');
                    $product_permalink = $_product->get_permalink();
                    
                    // Get variation attributes
                    $variation_attrs = array();
                    if ($_product->is_type('variation')) {
                        foreach ($cart_item['variation'] as $attr_key => $attr_value) {
                            $attr_name = wc_attribute_label(str_replace('attribute_', '', $attr_key), $_product);
                            $variation_attrs[] = $attr_name . ': ' . $attr_value;
                        }
                    }
                ?>
                <div class="mf-cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                    <a href="<?php echo esc_url($product_permalink); ?>" class="mf-cart-item__image-link" aria-label="<?php echo esc_attr($product_name); ?>">
                        <?php echo $product_image; ?>
                    </a>
                    <div class="mf-cart-item__info">
                        <h4 class="mf-cart-item__title">
                            <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_name); ?></a>
                        </h4>
                        <?php if ($variation_attrs) : ?>
                            <p class="mf-cart-item__details"><?php echo esc_html(implode(' | ', $variation_attrs)); ?></p>
                        <?php endif; ?>
                        <div class="mf-cart-item__price"><?php echo $product_price; ?></div>
                    </div>
                    <div class="mf-cart-item__quantity">
                        <form class="mf-cart-item-quantity-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                            <input type="hidden" name="cart_item_key" value="<?php echo esc_attr($cart_item_key); ?>">
                            <button type="button" class="mf-qty-btn mf-qty-btn--minus" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="Diminuir quantidade">−</button>
                            <input type="number" name="quantity" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="1" max="<?php echo esc_attr($_product->get_max_purchase_quantity()); ?>" class="mf-qty-input" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                            <button type="button" class="mf-qty-btn mf-qty-btn--plus" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="Aumentar quantidade">+</button>
                            <input type="hidden" name="update_cart" value="1">
                        </form>
                    </div>
                    <button type="button" class="mf-cart-item__remove" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="Remover <?php echo esc_attr($product_name); ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="mf-cart-empty" style="text-align: center; padding: var(--space-8) var(--space-5);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 64px; height: 64px; margin: 0 auto var(--space-4); color: var(--color-gray-400);">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1 0 8"></path>
                </svg>
                <h3 class="mf-empty-title">Sua sacola está vazia</h3>
                <p class="mf-empty-text">Adicione peças incríveis para começar.</p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary mf-mt-4">Continuar comprando</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($cart_count > 0) : ?>
    <div class="mf-mini-cart-drawer__footer">
        <div class="mf-cart-subtotal">
            <span class="mf-cart-subtotal-label">Subtotal</span>
            <span class="mf-cart-subtotal-value"><?php echo $cart_total; ?></span>
        </div>
        <p class="mf-cart-shipping-note">Frete calculado no checkout</p>
        <div class="mf-cart-actions">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mf-btn mf-btn--secondary mf-btn--block">Ver Carrinho</a>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="mf-btn mf-btn--primary mf-btn--block">Finalizar Compra</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Cart Overlay -->
<div class="mf-cart-overlay" id="mf-cart-overlay" aria-hidden="true"></div>