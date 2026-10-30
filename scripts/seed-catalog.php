<?php
/**
 * Catálogo mínimo idempotente para validar produto variável.
 * Não substitui a planilha de 300 produtos do cliente (seção 8 do PDF).
 *
 * Uso: wp eval-file /scripts/seed-catalog.php --user=1
 */

if (!defined('WP_CLI') || !WP_CLI) {
    fwrite(STDERR, "Execute com WP-CLI.\n");
    exit(1);
}

if (!class_exists('WooCommerce')) {
    WP_CLI::error('WooCommerce não está ativo.');
}

function mf_term($taxonomy, $name, $slug, $meta = array()) {
    $existing = get_term_by('slug', $slug, $taxonomy);
    if ($existing) {
        $term_id = (int) $existing->term_id;
    } else {
        $created = wp_insert_term($name, $taxonomy, array('slug' => $slug));
        if (is_wp_error($created)) {
            WP_CLI::warning($created->get_error_message());
            return 0;
        }
        $term_id = (int) $created['term_id'];
    }

    foreach ($meta as $key => $value) {
        update_term_meta($term_id, $key, $value);
    }

    return $term_id;
}

function mf_attribute($label, $slug) {
    $id = wc_attribute_taxonomy_id_by_name($slug);
    if (!$id) {
        $id = wc_create_attribute(array(
            'name' => $label,
            'slug' => $slug,
            'type' => 'select',
            'order_by' => 'menu_order',
            'has_archives' => false,
        ));
        if (is_wp_error($id)) {
            WP_CLI::error($id->get_error_message());
        }
    }

    delete_transient('wc_attribute_taxonomies');
    $taxonomy = wc_attribute_taxonomy_name($slug);

    if (!taxonomy_exists($taxonomy)) {
        register_taxonomy($taxonomy, array('product'), array(
            'hierarchical' => false,
            'show_ui' => false,
            'query_var' => true,
            'rewrite' => false,
        ));
    }

    return $taxonomy;
}

function mf_page($title, $slug, $content) {
    $found = get_page_by_path($slug);
    if ($found) {
        return (int) $found->ID;
    }

    return (int) wp_insert_post(array(
        'post_title' => $title,
        'post_name' => $slug,
        'post_status' => 'draft',
        'post_type' => 'page',
        'post_content' => $content,
    ));
}

$tamanho = mf_attribute('Tamanho', 'tamanho');
$cor = mf_attribute('Cor', 'cor');

$sizes = array(
    'pp' => 'PP',
    'p' => 'P',
    'm' => 'M',
    'g' => 'G',
    'gg' => 'GG',
);
$colors = array(
    'preto' => array('Preto', '#1a1a1a'),
    'off-white' => array('Off-white', '#f4efe6'),
    'rosa' => array('Rosa', '#c48cb3'),
);

$size_ids = array();
foreach ($sizes as $slug => $name) {
    $size_ids[$slug] = mf_term($tamanho, $name, $slug);
}

$color_ids = array();
foreach ($colors as $slug => $data) {
    $color_ids[$slug] = mf_term($cor, $data[0], $slug, array('mf_hex' => $data[1]));
}

$categories = array(
    'vestidos' => 'Vestidos',
    'blusas' => 'Blusas',
    'calcas' => 'Calças',
    'saias' => 'Saias',
    'acessorios' => 'Acessórios',
);
$category_ids = array();
foreach ($categories as $slug => $name) {
    $category_ids[$slug] = mf_term('product_cat', $name, $slug);
}

$draft = '<p><strong>Rascunho.</strong> O texto definitivo deve ser fornecido pela marca e revisado juridicamente antes da publicação. Este conteúdo não é uma política legal.</p>';
mf_page('Sobre a marca', 'sobre', '<p>Rascunho institucional. A marca deve enviar a história, o endereço da loja em São Paulo e os contatos.</p>');
mf_page('Trocas e devoluções', 'trocas-e-devolucoes', $draft . '<p>Incluir prazo de arrependimento de 7 dias, custos e procedimento de devolução.</p>');
mf_page('Política de privacidade', 'privacidade', $draft);
mf_page('Termos de uso', 'termos', $draft);
mf_page('Perguntas frequentes', 'perguntas-frequentes', '<p>Rascunho. Incluir prazo de entrega, trocas e pagamento.</p>');
mf_page('Contato', 'contato', '<p>Rascunho. Informar e-mail, telefone, CNPJ e endereço da loja física.</p>');

function mf_variable_product($sku, $name, $short, $description, $category_id, $size_taxonomy, $color_taxonomy, $size_ids, $color_ids, $variations) {
    $existing_id = wc_get_product_id_by_sku($sku);
    if ($existing_id) {
        WP_CLI::log("Produto {$sku} já existe (#{$existing_id}).");
        return;
    }

    $product = new WC_Product_Variable();
    $product->set_name($name);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_sku($sku);
    $product->set_short_description($short);
    $product->set_description($description);
    $product->set_category_ids(array($category_id));
    $product->set_featured(true);

    $size_attr = new WC_Product_Attribute();
    $size_attr->set_id((int) wc_attribute_taxonomy_id_by_name('tamanho'));
    $size_attr->set_name($size_taxonomy);
    $size_attr->set_options(array_values(array_filter($size_ids)));
    $size_attr->set_visible(true);
    $size_attr->set_variation(true);

    $color_attr = new WC_Product_Attribute();
    $color_attr->set_id((int) wc_attribute_taxonomy_id_by_name('cor'));
    $color_attr->set_name($color_taxonomy);
    $color_attr->set_options(array_values(array_filter($color_ids)));
    $color_attr->set_visible(true);
    $color_attr->set_variation(true);

    $product->set_attributes(array($size_attr, $color_attr));
    $product_id = $product->save();

    foreach ($variations as $row) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_status('publish');
        $variation->set_attributes(array(
            $size_taxonomy => $row['tamanho'],
            $color_taxonomy => $row['cor'],
        ));
        $variation->set_regular_price($row['preco']);
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity($row['estoque']);
        $variation->set_sku($row['sku']);
        $variation->set_weight('0.45');
        $variation->set_length('32');
        $variation->set_width('24');
        $variation->set_height('5');
        $variation->save();
    }

    WC_Product_Variable::sync($product_id);
    WP_CLI::success("Produto variável {$sku} criado (#{$product_id}).");
}

mf_variable_product(
    'VEST-001',
    'Vestido Floral Midi',
    'Vestido leve, cintura marcada e saia fluida. Exemplo para validar variação de tamanho e cor.',
    '<p>Composição: 100% viscose. Cuidados: lavar à mão e secar à sombra. A tabela de medidas definitiva depende da marca.</p>',
    $category_ids['vestidos'],
    $tamanho,
    $cor,
    $size_ids,
    $color_ids,
    array(
        array('sku' => 'VEST-001-ROSA-P', 'tamanho' => 'p', 'cor' => 'rosa', 'preco' => '289.90', 'estoque' => 6),
        array('sku' => 'VEST-001-ROSA-M', 'tamanho' => 'm', 'cor' => 'rosa', 'preco' => '289.90', 'estoque' => 0),
        array('sku' => 'VEST-001-PRETO-M', 'tamanho' => 'm', 'cor' => 'preto', 'preco' => '289.90', 'estoque' => 4),
    )
);

mf_variable_product(
    'BLUS-001',
    'Blusa de Seda Off-white',
    'Blusa de modelagem solta. Exemplo com estoque por variação.',
    '<p>Composição: 100% seda. Cuidados: lavagem a seco. Medidas definitivas dependem da planilha do cliente.</p>',
    $category_ids['blusas'],
    $tamanho,
    $cor,
    $size_ids,
    $color_ids,
    array(
        array('sku' => 'BLUS-001-OFF-P', 'tamanho' => 'p', 'cor' => 'off-white', 'preco' => '219.00', 'estoque' => 5),
        array('sku' => 'BLUS-001-OFF-M', 'tamanho' => 'm', 'cor' => 'off-white', 'preco' => '219.00', 'estoque' => 3),
        array('sku' => 'BLUS-001-PRETO-G', 'tamanho' => 'g', 'cor' => 'preto', 'preco' => '219.00', 'estoque' => 2),
    )
);

WP_CLI::success('Catálogo de exemplo aplicado. Ainda faltam os 300 produtos, fotos e textos do cliente.');
