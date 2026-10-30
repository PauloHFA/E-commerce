<?php
/**
 * Página inicial da loja. O header do Storefront já abre .col-full.
 */

get_header();
?>

</div>
<section class="mf-hero">
	<div class="col-full">
		<p class="mf-kicker">Loja de moda feminina</p>
		<h1>Peças para o dia e para sair</h1>
		<p>Escolha tamanho e cor. Esta vitrine é de exemplo: a coleção completa entra pela planilha da marca.</p>
		<a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Ver a loja</a>
	</div>
</section>
<div class="col-full">

	<section class="mf-home">
		<h2>Em destaque</h2>
		<?php echo do_shortcode('[products limit="4" columns="2" visibility="featured"]'); ?>
	</section>

<?php
get_footer();
