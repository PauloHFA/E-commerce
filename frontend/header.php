<?php
/**
 * Header Template - Premium Fashion E-commerce Header
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Source+Sans+3:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="hfeed site">

    <!-- Announcement Bar -->
    <div class="mf-announcement-bar" id="mf-announcement-bar" role="region" aria-label="Anúncios">
        <div class="mf-announcement-bar__content">
            <div class="mf-announcement-bar__item">
                <svg class="mf-announcement-bar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                </svg>
                <span>Frete grátis em compras acima de R$ 199</span>
            </div>
            <div class="mf-announcement-bar__item">
                <svg class="mf-announcement-bar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 9.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
                <span>Até 6x sem juros</span>
            </div>
            <div class="mf-announcement-bar__item">
                <svg class="mf-announcement-bar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <rect x="2" y="4" width="20" height="14" rx="2"></rect>
                    <line x1="6" y1="10" x2="6.01" y2="10"></line>
                </svg>
                <span>Troca fácil em 30 dias</span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="mf-header" id="mf-header" role="banner">
        <div class="mf-header__inner">
            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="mf-logo" aria-label="Moda Feminina - Início">
                <span class="mf-logo__text">Moda Feminina</span>
                <span class="mf-logo__tagline">Sua curadoria de estilo online</span>
            </a>

            <!-- Search -->
            <div class="mf-search" role="search">
                <form class="mf-search__form" action="<?php echo esc_url(home_url('/')); ?>" method="get">
                    <input 
                        type="search" 
                        class="mf-search__input" 
                        name="s" 
                        placeholder="Pesquisar produtos..." 
                        aria-label="Pesquisar produtos"
                        autocomplete="off"
                    >
                    <button type="submit" class="mf-search__btn" aria-label="Buscar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Header Actions -->
            <div class="mf-header-actions">
                <!-- Wishlist -->
                <button class="mf-header-action mf-header-action--wishlist" aria-label="Lista de desejos" data-wishlist-toggle>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </button>

                <!-- Account -->
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="mf-header-action" aria-label="Minha conta">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>

                <!-- Cart -->
                <button class="mf-header-action mf-header-action--cart" id="mf-mini-cart-toggle" aria-label="Carrinho de compras" aria-expanded="false" data-cart-toggle>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1 0 8"></path>
                    </svg>
                    <span class="mf-header-action__count" id="mf-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="mf-nav" id="mf-nav" role="navigation" aria-label="Navegação principal">
        <div class="mf-nav__list-wrapper">
            <ul class="mf-nav__list">
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="mf-nav__link <?php echo is_front_page() ? 'is-active' : ''; ?>">Início</a>
                </li>
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-nav__link <?php echo is_shop() ? 'is-active' : ''; ?>">Loja</a>
                </li>
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop') . '?orderby=date'); ?>" class="mf-nav__link">Novidades</a>
                </li>
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>" class="mf-nav__link">Carrinho</a>
                </li>
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="mf-nav__link">Minha Conta</a>
                </li>
                <li class="mf-nav__item">
                    <a href="<?php echo esc_url(home_url('/sobre')); ?>" class="mf-nav__link">Sobre Nós</a>
                </li>
            </ul>
        </div>
        
        <!-- Mobile Menu Toggle -->
        <button class="mf-mobile-menu-toggle" id="mf-mobile-menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="mf-nav">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </nav>

    <div class="col-full">