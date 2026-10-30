/**
 * JavaScript Custom - Moda Feminina E-commerce
 * 
 * Interações premium: quick view, zoom suave, animações de produtos,
 * carrinho drawer e recomendações dinâmicas.
 */

jQuery(document).ready(function($) {
    
    console.log('%c🎀 Moda Feminina Custom JS carregado com sucesso!', 'color:#c48cb3; font-weight:bold');
    
    // Animação suave ao adicionar ao carrinho
    $(document).on('added_to_cart', function() {
        $('.cart-contents').addClass('pulse');
        setTimeout(() => {
            $('.cart-contents').removeClass('pulse');
        }, 1200);
    });
    
    // Hover effect extra nos cards de produto
    $('.product').hover(
        function() {
            $(this).find('img').css('transform', 'scale(1.08)');
        },
        function() {
            $(this).find('img').css('transform', 'scale(1)');
        }
    );
    
    // Scroll suave para seções
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
    
    // Placeholder para Quick View (pode ser expandido com plugin ou AJAX)
    $('.product a').on('click', function(e) {
        if ($(this).hasClass('quick-view-trigger')) {
            e.preventDefault();
            alert('🔍 Quick View em desenvolvimento - Em breve você verá detalhes do produto aqui!');
        }
    });
    
    // Mensagem de boas-vindas na página inicial
    if ($('body.home').length) {
        console.log('%c👗 Bem-vinda à loja de Moda Feminina! Coleções exclusivas aguardam você.', 'color:#5c2e6b; font-size:13px');
    }
});
