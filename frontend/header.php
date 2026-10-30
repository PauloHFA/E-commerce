<?php
/**
 * Header Template - Override Storefront header to add mini-cart
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="hfeed site">
    <header id="masthead" class="site-header" role="banner">
        <div class="col-full">
            
            <?php
            /**
             * Functions hooked in to storefront_header action
             *
             * @hooked storefront_header_container - 0
             * @hooked storefront_skip_links - 5
             * @hooked storefront_site_branding - 20
             * @hooked storefront_secondary_navigation - 30
             * @hooked storefront_primary_navigation_wrapper - 42
             * @hooked storefront_primary_navigation - 50
             * @hooked storefront_primary_navigation_wrapper_close - 68
             * @hooked storefront_header_container_close - 68
             * @hooked storefront_handheld_footer_bar - 999
             */
            do_action('storefront_header');
            ?>
            
            <!-- Mini Cart Toggle -->
            <div class="mf-header-actions">
                <div class="mf-mini-cart-toggle" id="mf-mini-cart-toggle" data-cart-toggle role="button" tabindex="0" aria-label="Abrir carrinho" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1 0 8"></path>
                    </svg>
                    <span class="mf-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </div>
            </div>
            
        </div>
    </header>

    <div class="col-full">