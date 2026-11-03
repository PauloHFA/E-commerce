<?php
/**
 * Professional Homepage - Premium Fashion E-commerce
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="col-full">
<main id="main-content" role="main">

<!-- Hero Banner -->
<section class="mf-hero" aria-labelledby="hero-title">
    <div class="mf-hero__bg">
        <img class="mf-hero__image" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/hero/hero-banner.svg'); ?>" alt="" aria-hidden="true">
        <div class="mf-hero__overlay"></div>
    </div>
    <div class="mf-hero__content">
            <span class="mf-hero__kicker">NOVA COLEÃ‡ÃƒO / primavera â€¢ verÃ£o</span>
        <h1 id="hero-title" class="mf-hero__title">PeÃ§as que contam sua histÃ³ria</h1>
        <p class="mf-hero__subtitle">Moda feminina elegante, sustentÃ¡vel e feita para durar. Descubra peÃ§as versÃ¡teis que transitam do dia para a noite.</p>
        <div class="mf-hero__cta">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary mf-btn--lg mf-hero__btn mf-hero__btn--primary">CONFIRA AS NOVIDADES â†’</a>
            <a href="#newsletter" class="mf-btn mf-btn--ghost mf-btn--lg mf-hero__btn mf-hero__btn--ghost">Ver Lookbook</a>
        </div>
        <div class="mf-hero__trust">
            <div class="mf-hero__trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                <span>Frete grÃ¡tis acima de R$ 199</span>
            </div>
            <div class="mf-hero__trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 9.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <span>Troca fÃ¡cil em 30 dias</span>
            </div>
            <div class="mf-hero__trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="14" rx="2"></rect><line x1="6" y1="10" x2="6.01" y2="10"></line></svg>
                <span>Pagamento seguro</span>
            </div>
            <div class="mf-hero__trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>
                <span>Suporte humanizado</span>
            </div>
        </div>
    </div>
    <button class="mf-hero__scroll" aria-label="Rolar para baixo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </button>
</section>

    <!-- Featured Collections -->
    <section class="mf-collections" aria-labelledby="collections-heading">
        <div class="mf-container">
            <header class="mf-section-header">
                <span class="mf-section-header__kicker">Categorias</span>
                <h2 id="collections-heading" class="mf-section-header__title">Nossas ColeÃ§Ãµes</h2>
                <p class="mf-section-header__subtitle">Curadas com carinho para cada momento</p>
            </header>
            
            <div class="mf-collections__grid">
                <?php
                $collections = array(
                    array(
                        'title' => 'Essenciais',
                        'subtitle' => 'BÃ¡sicos premium',
                                        'image' => get_stylesheet_directory_uri() . '/images/collections/collection-essentials.svg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=essenciais',
                    ),
                    array(
                        'title' => 'Vestidos',
                        'subtitle' => 'Do casual ao festa',
                                        'image' => get_stylesheet_directory_uri() . '/images/collections/collection-dresses.svg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=vestidos',
                    ),
                    array(
                        'title' => 'Alfaiataria',
                        'subtitle' => 'Estrutura e elegÃ¢ncia',
                                        'image' => get_stylesheet_directory_uri() . '/images/collections/collection-tailoring.svg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=alfaiataria',
                    ),
                    array(
                        'title' => 'AcessÃ³rios',
                        'subtitle' => 'O toque final',
                                        'image' => get_stylesheet_directory_uri() . '/images/collections/collection-accessories.svg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=acessorios',
                    ),
                );
                
                foreach ($collections as $index => $collection) :
                ?>
                <article class="mf-collection-card mf-animate-on-scroll mf-stagger-<?php echo $index + 1; ?>">
                    <div class="mf-collection-card__bg" style="background-image: url('<?php echo esc_url($collection['image']); ?>');" aria-hidden="true"></div>
                    <div class="mf-collection-card__overlay"></div>
                    <a href="<?php echo esc_url($collection['link']); ?>" class="mf-collection-card__content" aria-label="Ver coleÃ§Ã£o <?php echo esc_attr($collection['title']); ?>">
                        <span class="mf-collection-card__subtitle"><?php echo esc_html($collection['subtitle']); ?></span>
                        <h3 class="mf-collection-card__title"><?php echo esc_html($collection['title']); ?></h3>
                        <span class="mf-collection-card__cta">Explorar <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></span>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="mf-featured-products" aria-labelledby="featured-heading">
        <div class="mf-container">
            <header class="mf-section-header mf-flex mf-flex--between">
                <div>
                    <span class="mf-section-header__kicker">Em Destaque</span>
                    <h2 id="featured-heading" class="mf-section-header__title">Nossas peÃ§as mais amadas</h2>
                </div>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--ghost mf-section-header__action">Ver todos os produtos</a>
            </header>
            
            <div class="mf-products-grid" data-products-grid>
                <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured" orderby="date" order="desc"]'); ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section id="newsletter" class="mf-newsletter" aria-labelledby="newsletter-heading">
        <div class="mf-newsletter__inner">
            <div class="mf-newsletter__content">
                <span class="mf-newsletter__kicker">Newsletter</span>
                <h2 id="newsletter-heading" class="mf-newsletter__title">FaÃ§a parte do nosso mundo</h2>
                <p class="mf-newsletter__text">Receba novidades, acesso antecipado a lanÃ§amentos e 10% de desconto na primeira compra.</p>
                <div class="mf-newsletter__benefits">
                    <div class="mf-newsletter__benefit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Acesso antecipado</span>
                    </div>
                    <div class="mf-newsletter__benefit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Descontos exclusivos</span>
                    </div>
                    <div class="mf-newsletter__benefit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Dicas de estilo</span>
                    </div>
                </div>
            </div>
            <form class="mf-newsletter__form" action="#" method="post" novalidate>
                <div class="mf-newsletter__form-group">
                    <input type="email" name="email" class="mf-newsletter__input" placeholder="Seu melhor e-mail" required autocomplete="email" aria-label="Seu melhor e-mail">
                    <button type="submit" class="mf-btn mf-btn--primary mf-newsletter__btn">Inscrever-se</button>
                </div>
                <p class="mf-newsletter__privacy">Respeitamos sua privacidade. <a href="#">PolÃ­tica de privacidade</a>.</p>
            </form>
        </div>
    </section>

    <!-- Instagram Feed Placeholder -->
    <section class="mf-instagram" aria-labelledby="instagram-heading">
        <div class="mf-container">
            <header class="mf-section-header">
                <span class="mf-section-header__kicker">InspiraÃ§Ã£o</span>
                <h2 id="instagram-heading" class="mf-section-header__title">InspiraÃ§Ã£o do Dia</h2>
                <p class="mf-section-header__subtitle">Siga-nos @modafeminina</p>
            </header>
            
            <div class="mf-instagram__grid" data-instagram-feed>
                <?php
                $instagram_images = array(
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-1.svg',
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-2.svg',
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-3.svg',
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-4.svg',
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-5.svg',
                    get_stylesheet_directory_uri() . '/images/instagram/instagram-6.svg',
                );
                
                foreach ($instagram_images as $index => $image) : ?>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="mf-instagram__item mf-animate-on-scroll mf-stagger-<?php echo $index + 1; ?>" aria-label="Post do Instagram <?php echo $index + 1; ?>">
                    <div class="mf-instagram__image">
                        <img src="<?php echo esc_url($image); ?>" alt="" aria-hidden="true" loading="lazy">
                    </div>
                    <div class="mf-instagram__overlay">
                        <span class="mf-instagram__stats">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>1.2k</span>
                        </span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <div class="mf-instagram__cta">
                <a href="https://instagram.com" target="_blank" rel="noopener" class="mf-btn mf-btn--ghost mf-btn--lg">Ver mais no Instagram</a>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="mf-footer-cta" aria-labelledby="footer-cta-heading">
        <div class="mf-footer-cta__inner">
            <h2 id="footer-cta-heading" class="mf-footer-cta__title">Pronto para renovar seu guarda-roupa?</h2>
            <p class="mf-footer-cta__text">Descubra peÃ§as que valorizam sua essÃªncia. Frete grÃ¡tis acima de R$ 199.</p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary mf-btn--lg">ComeÃ§ar a comprar</a>
        </div>
    </section>

</main>
</div>

<?php
get_footer();
