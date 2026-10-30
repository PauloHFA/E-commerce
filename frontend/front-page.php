<?php
/**
 * Professional Homepage - SPA-like experience
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

</div>

<!-- Hero Section -->
<section class="mf-hero" aria-labelledby="hero-heading">
    <div class="mf-hero-bg" aria-hidden="true">
        <div class="mf-hero-gradient"></div>
        <div class="mf-hero-pattern"></div>
    </div>
    <div class="col-full mf-hero-content">
        <div class="mf-hero-inner">
            <p class="mf-kicker mf-animate-fade-in">Nova Coleção Primavera/Verão</p>
            <h1 id="hero-heading" class="mf-animate-slide-up">Peças que contam sua história</h1>
            <p class="mf-hero-subtitle mf-animate-slide-up" style="animation-delay: 0.2s;">Moda feminina elegante, sustentável e feita para durar. Descubra peças versáteis que transitam do dia para a noite.</p>
            <div class="mf-hero-cta mf-animate-slide-up" style="animation-delay: 0.4s;">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary mf-btn--lg">Explorar Coleção</a>
                <a href="#newsletter" class="mf-btn mf-btn--ghost mf-btn--lg">Ver Lookbook</a>
            </div>
            <div class="mf-hero-trust mf-animate-slide-up" style="animation-delay: 0.6s;">
                <div class="mf-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"></path></svg>
                    <span>Frete grátis acima de R$ 299</span>
                </div>
                <div class="mf-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 9.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <span>Troca fácil em 30 dias</span>
                </div>
                <div class="mf-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="14" rx="2"></rect><line x1="6" y1="10" x2="6.01" y2="10"></line></svg>
                    <span>Pagamento seguro</span>
                </div>
                <div class="mf-trust-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4M12 8h.01"></path></svg>
                    <span>Suporte humanizado</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mf-hero-scroll" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
    </div>
</section>

<div class="col-full">

    <!-- Featured Collections -->
    <section class="mf-collections" aria-labelledby="collections-heading">
        <div class="mf-container">
            <header class="mf-section-header">
                <h2 id="collections-heading" class="mf-section-title">Nossas Coleções</h2>
                <p class="mf-section-subtitle">Curadas com carinho para cada momento</p>
            </header>
            
            <div class="mf-collections-grid">
                <?php
                $collections = array(
                    array(
                        'title' => 'Essenciais',
                        'subtitle' => 'Básicos premium',
                        'image' => get_stylesheet_directory_uri() . '/assets/images/collection-essentials.jpg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=essenciais',
                        'color' => 'linear-gradient(135deg, #f8f5f2 0%, #e7d7e3 100%)'
                    ),
                    array(
                        'title' => 'Vestidos',
                        'subtitle' => 'Do casual ao festa',
                        'image' => get_stylesheet_directory_uri() . '/assets/images/collection-dresses.jpg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=vestidos',
                        'color' => 'linear-gradient(135deg, #f4a8b2 0%, #c48cb3 100%)'
                    ),
                    array(
                        'title' => 'Alfaiataria',
                        'subtitle' => 'Estrutura e elegância',
                        'image' => get_stylesheet_directory_uri() . '/assets/images/collection-tailoring.jpg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=alfaiataria',
                        'color' => 'linear-gradient(135deg, #5c2e6b 0%, #3a2a4a 100%)'
                    ),
                    array(
                        'title' => 'Acessórios',
                        'subtitle' => 'O toque final',
                        'image' => get_stylesheet_directory_uri() . '/assets/images/collection-accessories.jpg',
                        'link' => wc_get_page_permalink('shop') . '?product_cat=acessorios',
                        'color' => 'linear-gradient(135deg, #e8b86d 0%, #d4c1d1 100%)'
                    ),
                );
                
                foreach ($collections as $index => $collection) :
                ?>
                <article class="mf-collection-card mf-animate-on-scroll" style="--bg: <?php echo esc_attr($collection['color']); ?>; animation-delay: <?php echo $index * 0.1; ?>s;">
                    <div class="mf-collection-bg" style="background: <?php echo esc_attr($collection['color']); ?>;" aria-hidden="true"></div>
                    <div class="mf-collection-overlay"></div>
                    <a href="<?php echo esc_url($collection['link']); ?>" class="mf-collection-link" aria-label="Ver coleção <?php echo esc_attr($collection['title']); ?>">
                        <div class="mf-collection-content">
                            <span class="mf-collection-subtitle"><?php echo esc_html($collection['subtitle']); ?></span>
                            <h3 class="mf-collection-title"><?php echo esc_html($collection['title']); ?></h3>
                            <span class="mf-collection-cta">Explorar <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></span>
                        </div>
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
                    <h2 id="featured-heading" class="mf-section-title">Em Destaque</h2>
                    <p class="mf-section-subtitle">Nossas peças mais amadas</p>
                </div>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--ghost">Ver todos os produtos</a>
            </header>
            
            <div class="mf-products-grid" data-products-grid>
                <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured" orderby="date" order="desc"]'); ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section id="newsletter" class="mf-newsletter" aria-labelledby="newsletter-heading">
        <div class="mf-container">
            <div class="mf-newsletter-inner">
                <div class="mf-newsletter-content">
                    <h2 id="newsletter-heading" class="mf-section-title">Faça parte do nosso mundo</h2>
                    <p class="mf-newsletter-text">Receba novidades, acesso antecipado a lançamentos e 10% de desconto na primeira compra.</p>
                    <div class="mf-newsletter-benefits">
                        <div class="mf-benefit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Acesso antecipado</span>
                        </div>
                        <div class="mf-benefit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Descontos exclusivos</span>
                        </div>
                        <div class="mf-benefit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Dicas de estilo</span>
                        </div>
                    </div>
                </div>
                <form class="mf-newsletter-form" action="#" method="post" novalidate>
                    <div class="mf-form-group">
                        <label for="newsletter-email" class="screen-reader-text">Seu melhor e-mail</label>
                        <input type="email" id="newsletter-email" name="email" class="mf-form-control" placeholder="Seu melhor e-mail" required autocomplete="email">
                        <button type="submit" class="mf-btn mf-btn--primary mf-btn--lg">Inscrever-se</button>
                    </div>
                    <p class="mf-newsletter-privacy">Respeitamos sua privacidade. <a href="#">Política de privacidade</a>.</p>
                </form>
            </div>
        </div>
    </section>

    <!-- Instagram Feed Placeholder -->
    <section class="mf-instagram" aria-labelledby="instagram-heading">
        <div class="mf-container">
            <header class="mf-section-header">
                <h2 id="instagram-heading" class="mf-section-title">Inspiração do Dia</h2>
                <p class="mf-section-subtitle">Siga-nos @modafeminina</p>
            </header>
            
            <div class="mf-instagram-grid" data-instagram-feed>
                <?php for ($i = 1; $i <= 6; $i++) : ?>
                <a href="https://instagram.com" target="_blank" rel="noopener" class="mf-instagram-item mf-animate-on-scroll" style="animation-delay: <?php echo $i * 0.05; ?>s;" aria-label="Post do Instagram <?php echo $i; ?>">
                    <div class="mf-instagram-image" style="background: var(--color-gray-200);">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44 1.44-.645 1.44-1.44-.645-1.44-1.441-1.44z"></path></svg>
                    </div>
                    <div class="mf-instagram-overlay">
                        <span class="mf-instagram-stats">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>1.2k</span>
                        </span>
                    </div>
                </a>
                <?php endfor; ?>
            </div>
            <div class="mf-instagram-cta">
                <a href="https://instagram.com" target="_blank" rel="noopener" class="mf-btn mf-btn--ghost mf-btn--lg">Ver mais no Instagram</a>
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="mf-footer-cta" aria-labelledby="footer-cta-heading">
        <div class="mf-container">
            <div class="mf-footer-cta-inner">
                <h2 id="footer-cta-heading" class="mf-section-title">Pronto para renovar seu guarda-roupa?</h2>
                <p class="mf-footer-cta-text">Descubra peças que valorizam sua essência. Frete grátis acima de R$ 299.</p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary mf-btn--lg">Começar a comprar</a>
            </div>
        </div>
    </section>

<?php
get_footer();
