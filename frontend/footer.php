<?php
/**
 * Footer Template - Premium Fashion E-commerce Footer
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

        </div><!-- .col-full -->
    </div><!-- #page -->

    <!-- Premium Footer -->
    <footer class="mf-footer" role="contentinfo">
        <div class="mf-footer__top">
            <div class="mf-container">
                <div class="mf-footer__grid">
                    <!-- Brand Column -->
                    <div class="mf-footer__col mf-footer__col--brand">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="mf-footer__logo" aria-label="Moda Feminina - Início">
                            <span class="mf-footer__logo-text">Moda Feminina</span>
                        </a>
                        <p class="mf-footer__tagline">Sua curadoria de estilo online. Peças selecionadas com cuidado para valorizar sua essência.</p>
                        <div class="mf-footer__social" aria-label="Redes sociais">
                            <a href="#" class="mf-footer__social-link" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                            <a href="#" class="mf-footer__social-link" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                            <a href="#" class="mf-footer__social-link" aria-label="Pinterest" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <line x1="12" y1="17" x2="12" y2="22"></line>
                                    <path d="M5 17h14v-2a2 2 0 0 0-2-2H7a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h2V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Columns -->
                    <nav class="mf-footer__col" aria-label="Atendimento">
                        <h3 class="mf-footer__heading">Atendimento</h3>
                        <ul class="mf-footer__links">
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">Minha Conta</a></li>
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>">Meu Carrinho</a></li>
                            <li><a href="#">Rastrear Pedido</a></li>
                            <li><a href="#">Trocas e Devoluções</a></li>
                            <li><a href="#">Perguntas Frequentes</a></li>
                            <li><a href="#">Fale Conosco</a></li>
                        </ul>
                    </nav>

                    <nav class="mf-footer__col" aria-label="Institucional">
                        <h3 class="mf-footer__heading">Institucional</h3>
                        <ul class="mf-footer__links">
                            <li><a href="#">Sobre Nós</a></li>
                            <li><a href="#">Nossas Lojas</a></li>
                            <li><a href="#">Trabalhe Conosco</a></li>
                            <li><a href="#">Sustentabilidade</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Imprensa</a></li>
                        </ul>
                    </nav>

                    <nav class="mf-footer__col" aria-label="Políticas">
                        <h3 class="mf-footer__heading">Políticas</h3>
                        <ul class="mf-footer__links">
                            <li><a href="#">Política de Privacidade</a></li>
                            <li><a href="#">Termos de Uso</a></li>
                            <li><a href="#">Política de Cookies</a></li>
                            <li><a href="#">LGPD</a></li>
                            <li><a href="#">Código de Ética</a></li>
                        </ul>
                    </nav>

                    <!-- Newsletter Column -->
                    <div class="mf-footer__col mf-footer__col--newsletter">
                        <h3 class="mf-footer__heading">Newsletter</h3>
                        <p class="mf-footer__newsletter-text">Receba novidades, lançamentos e ofertas exclusivas.</p>
                        <form class="mf-footer__newsletter-form" action="#" method="post">
                            <label for="mf-footer-email" class="screen-reader-text">E-mail</label>
                            <input type="email" id="mf-footer-email" name="email" class="mf-footer__newsletter-input" placeholder="Seu e-mail" required autocomplete="email">
                            <button type="submit" class="mf-footer__newsletter-btn" aria-label="Inscrever-se">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </button>
                        </form>
                        <p class="mf-footer__newsletter-note">Não enviamos spam. <a href="#">Política de privacidade</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mf-footer__bottom">
            <div class="mf-container">
                <div class="mf-footer__bottom-inner">
                    <p class="mf-footer__copyright">&copy; <?php echo date('Y'); ?> Moda Feminina. Todos os direitos reservados.</p>
                    <div class="mf-footer__payments" aria-label="Formas de pagamento aceitas">
                        <span class="mf-footer__payments-label">Pagamentos seguros:</span>
                        <div class="mf-footer__payment-icons">
                            <svg class="mf-footer__payment-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-label="Visa"><path d="M21 6H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h18a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"></path><text x="2" y="16" font-size="10" font-weight="bold">VISA</text></svg>
                            <svg class="mf-footer__payment-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-label="Mastercard"><circle cx="10" cy="12" r="6"></circle><circle cx="18" cy="12" r="6"></circle><path d="M2 12h6"></path></svg>
                            <svg class="mf-footer__payment-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-label="Elo"><text x="2" y="16" font-size="10" font-weight="bold">ELO</text></svg>
                            <svg class="mf-footer__payment-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-label="Pix"><text x="2" y="16" font-size="10" font-weight="bold">PIX</text></svg>
                            <svg class="mf-footer__payment-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-label="Boleto"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Overlay -->
    <div class="mf-cart-overlay" id="mf-cart-overlay" aria-hidden="true"></div>

        <!-- Mini Cart Drawer -->
        <div class="mf-mini-cart-drawer" id="mf-mini-cart-drawer" role="dialog" aria-label="Carrinho de compras" aria-hidden="true">
            <div class="mf-mini-cart-drawer__header">
                <h2 class="mf-mini-cart-drawer__title">Carrinho</h2>
                <button class="mf-mini-cart-drawer__close" aria-label="Fechar carrinho" data-cart-close>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="mf-mini-cart-drawer__body">
                <?php wc_get_template('cart/mini-cart.php'); ?>
            </div>
            <div class="mf-mini-cart-drawer__footer">
                <div class="mf-mini-cart-drawer__totals">
                    <div class="mf-mini-cart-drawer__total-row">
                        <span>Subtotal</span>
                        <span class="mf-mini-cart-drawer__subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                    <div class="mf-mini-cart-drawer__total-row mf-mini-cart-drawer__total-row--grand">
                        <span>Total</span>
                        <span class="mf-mini-cart-drawer__total"><?php echo WC()->cart->get_cart_total(); ?></span>
                    </div>
                </div>
                <div class="mf-mini-cart-drawer__actions">
                    <a href="<?php echo esc_url(wc_get_page_permalink('cart')); ?>" class="mf-btn mf-btn--outline mf-btn--full mf-mini-cart-drawer__btn mf-mini-cart-drawer__btn--view">Ver Carrinho</a>
                    <a href="<?php echo esc_url(wc_get_page_permalink('checkout')); ?>" class="mf-btn mf-btn--primary mf-btn--full mf-mini-cart-drawer__btn">Finalizar Compra</a>
                </div>
            </div>
        </div>

        <?php wp_footer(); ?>
    </body>
    </html>