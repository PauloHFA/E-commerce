<?php
/**
 * Product Archive Template - Shop Page with SPA-like filtering
 *
 * @package Moda_Feminina
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="mf-shop-wrapper">
    <div class="col-full">
        <main id="main-content" role="main">
        
        <!-- Page Header -->
        <header class="mf-shop-header">
            <div class="mf-shop-header-inner">
                <div class="mf-shop-title-area">
                    <?php
                    if (is_product_category()) {
                        $term = get_queried_object();
                        ?>
                        <h1 class="mf-shop-title"><?php single_term_title(); ?></h1>
                        <?php if ($term->description) : ?>
                            <div class="mf-shop-description"><?php echo term_description(); ?></div>
                        <?php endif;
                    } elseif (is_shop()) {
                        ?>
                        <h1 class="mf-shop-title"><?php woocommerce_page_title(); ?></h1>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Mobile Filter Toggle -->
                <button type="button" class="mf-filter-toggle" data-filter-toggle aria-expanded="false" aria-controls="mf-filter-sidebar">
                    <svg class="mf-icon-filter" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    <span>Filtros</span>
                    <span class="mf-filter-count" aria-live="polite" hidden></span>
                </button>
            </div>
        </header>

        <div class="mf-shop-grid">
            
            <!-- Filter Sidebar (Off-canvas on mobile) -->
            <aside class="mf-filter-sidebar" id="mf-filter-sidebar" role="complementary" aria-label="Filtros de produtos">
                <div class="mf-filter-sidebar-header">
                    <h2 class="mf-filter-sidebar-title">Filtros</h2>
                    <button type="button" class="mf-filter-close" data-filter-close aria-label="Fechar filtros">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                
                <div class="mf-filter-sidebar-body">
                    <?php
                    // Render filters using existing function
                    if (function_exists('moda_feminina_shop_filters')) {
                        moda_feminina_shop_filters();
                    }
                    
                    // Additional filter: Price Range Slider
                    ?>
                    <div class="mf-filter-section">
                        <h3 class="mf-filter-section-title">Preço</h3>
                        <div class="mf-price-slider-wrapper">
                            <div class="mf-price-inputs">
                                <label for="min-price" class="screen-reader-text">Preço mínimo</label>
                                <input type="number" id="min-price" class="mf-price-input" placeholder="Mín" min="0" step="1" aria-label="Preço mínimo">
                                <span class="mf-price-sep" aria-hidden="true">—</span>
                                <label for="max-price" class="screen-reader-text">Preço máximo</label>
                                <input type="number" id="max-price" class="mf-price-input" placeholder="Máx" min="0" step="1" aria-label="Preço máximo">
                            </div>
                            <div class="mf-price-slider" data-price-slider role="slider" aria-label="Intervalo de preço" aria-valuemin="0" aria-valuemax="1000" aria-valuenow="0" tabindex="0"></div>
                            <div class="mf-price-labels">
                                <span>R$ 0</span>
                                <span>R$ 1000+</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter: Categories -->
                    <div class="mf-filter-section">
                        <h3 class="mf-filter-section-title">Categorias</h3>
                        <ul class="mf-filter-list" aria-label="Categorias">
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true,
                                'parent' => 0,
                            ));
                            foreach ($categories as $cat) :
                                $active = isset($_GET['product_cat']) && $_GET['product_cat'] === $cat->slug;
                                $url = moda_feminina_filter_url(array('product_cat' => $active ? null : $cat->slug));
                            ?>
                                <li>
                                    <label class="mf-filter-checkbox">
                                        <input type="checkbox" name="product_cat" value="<?php echo esc_attr($cat->slug); ?>" <?php echo $active ? 'checked' : ''; ?>>
                                        <span class="mf-filter-checkbox-visual"></span>
                                        <span class="mf-filter-checkbox-label"><?php echo esc_html($cat->name); ?></span>
                                        <span class="mf-filter-count"><?php echo $cat->count; ?></span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- Filter: Attributes (Size, Color) -->
                    <?php
                    $attributes = array('pa_tamanho' => 'Tamanho', 'pa_cor' => 'Cor');
                    foreach ($attributes as $taxonomy => $label) :
                        $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => true));
                        if ($terms && !is_wp_error($terms)) :
                    ?>
                    <div class="mf-filter-section">
                        <h3 class="mf-filter-section-title"><?php echo esc_html($label); ?></h3>
                        <div class="mf-filter-attributes" role="group" aria-label="<?php echo esc_attr($label); ?>">
                            <?php foreach ($terms as $term) :
                                $active = isset($_GET[$taxonomy]) && $_GET[$taxonomy] === $term->slug;
                                $url = moda_feminina_filter_url(array($taxonomy => $active ? null : $term->slug));
                                $hex = get_term_meta($term->term_id, 'mf_hex', true);
                            ?>
                                <label class="mf-filter-attr <?php echo $taxonomy === 'pa_cor' ? 'mf-filter-color' : ''; ?>">
                                    <input type="checkbox" name="<?php echo esc_attr($taxonomy); ?>" value="<?php echo esc_attr($term->slug); ?>" <?php echo $active ? 'checked' : ''; ?>>
                                    <span class="mf-filter-attr-visual" <?php echo $hex ? 'style="background-color:' . esc_attr($hex) . '"' : ''; ?>></span>
                                    <span class="mf-filter-attr-label"><?php echo esc_html($term->name); ?></span>
                                    <span class="mf-filter-count"><?php echo $term->count; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                    
                    <!-- Filter Actions -->
                    <div class="mf-filter-actions">
                        <button type="button" class="mf-btn mf-btn--secondary mf-btn--block" data-filter-apply>Aplicar filtros</button>
                        <a href="<?php echo esc_url(moda_feminina_filter_url(array(
                            'filter_tamanho' => null,
                            'filter_cor' => null,
                            'min_price' => null,
                            'max_price' => null,
                            'product_cat' => null,
                        ))); ?>" class="mf-btn mf-btn--ghost mf-btn--block">Limpar todos</a>
                    </div>
                </div>
            </aside>

            <!-- Filter Overlay (Mobile) -->
            <div class="mf-filter-overlay" data-filter-overlay hidden></div>

            <!-- Main Content Area -->
                        <div class="mf-shop-main">
                
                <!-- Toolbar -->
                <div class="mf-shop-toolbar" data-toolbar>
                    <div class="mf-toolbar-left">
                        <p class="mf-results-count" aria-live="polite">
                            <?php
                            global $wp_query;
                            $total = $wp_query->found_posts;
                            $per_page = get_option('posts_per_page');
                            $paged = max(1, get_query_var('paged'));
                            $start = ($paged - 1) * $per_page + 1;
                            $end = min($paged * $per_page, $total);
                            printf(
                                _n('Mostrando %d de %d produto', 'Mostrando %d a %d de %d produtos', $total, 'moda-feminina'),
                                $start, $end, $total
                            );
                            ?>
                        </p>
                    </div>
                    
                    <div class="mf-toolbar-right">
                        <!-- View Toggle -->
                        <div class="mf-view-toggle" role="group" aria-label="Visualização">
                            <button type="button" class="mf-view-btn is-active" data-view="grid" aria-label="Grade" aria-pressed="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg>
                            </button>
                            <button type="button" class="mf-view-btn" data-view="list" aria-label="Lista" aria-pressed="false">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                            </button>
                        </div>
                        
                        <!-- Sort Dropdown -->
                        <div class="mf-sort-wrapper">
                            <label for="mf-sort" class="screen-reader-text">Ordenar por</label>
                            <select id="mf-sort" class="mf-sort-select" data-sort>
                                <?php
                                $orderby_options = array(
                                    'menu_order' => 'Padrão',
                                    'popularity' => 'Mais vendidos',
                                    'rating' => 'Melhor avaliados',
                                    'date' => 'Mais recentes',
                                    'price' => 'Preço: menor para maior',
                                    'price-desc' => 'Preço: maior para menor',
                                );
                                $current_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
                                foreach ($orderby_options as $value => $label) :
                                ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($current_orderby, $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="mf-products-grid" data-products-grid>
                    <?php
                    if (have_posts()) :
                        woocommerce_product_loop_start();
                        while (have_posts()) : the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        woocommerce_product_loop_end();
                    else :
                        // Empty State
                    ?>
                    <div class="mf-empty-state" data-empty-state>
                        <svg class="mf-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <h2 class="mf-empty-title">Nenhum produto encontrado</h2>
                        <p class="mf-empty-text">Tente ajustar seus filtros ou explore nossas categorias.</p>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mf-btn mf-btn--primary">Ver todos os produtos</a>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>

                <!-- Load More / Pagination -->
                <div class="mf-pagination-wrapper" data-pagination>
                    <?php
                    $pagination_args = array(
                        'prev_text' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>',
                        'next_text' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
                        'type' => 'list',
                        'screen_reader_text' => 'Navegação de produtos',
                    );
                    woocommerce_pagination($pagination_args);
                    ?>
                    
                    <!-- Load More Button (for infinite scroll) -->
                    <button type="button" class="mf-btn mf-btn--secondary mf-load-more" data-load-more hidden>
                        <span class="mf-btn-text">Carregar mais</span>
                        <span class="mf-btn-loader" aria-hidden="true">
                            <svg class="mf-spinner" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="31.4 31.4" stroke-linecap="round"></circle></svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>