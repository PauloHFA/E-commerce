<?php
/**
 * Footer Template - Override Storefront footer
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

        </div><!-- .col-full -->
    </div><!-- #page -->

    <!-- Mini Cart Drawer -->
    <?php wc_get_template('cart/mini-cart.php'); ?>

    <?php
    /**
     * Functions hooked in to storefront_footer action
     *
     * @hooked storefront_footer_widgets - 10
     * @hooked storefront_credit - 20
     */
    do_action('storefront_footer');
    ?>

    <?php wp_footer(); ?>
</body>
</html>