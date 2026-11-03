<?php
/**
 * Importa o catálogo no formato de docs/modelo-planilha-produtos.csv.
 *
 * A planilha real continua sendo a do cliente. Este script só lê o CSV
 * informado e faz upsert por SKU. Não apaga produtos que não estejam no arquivo.
 *
 * Uso:
 *   wp eval-file /scripts/import-catalog.php /scripts/catalogo-amostra.csv --user=1
 *   wp eval-file /scripts/import-catalog.php /scripts/catalogo-amostra.csv limit=5 --user=1
 *   wp eval-file /scripts/import-catalog.php /scripts/catalogo-amostra.csv dry-run --user=1
 */

if (!defined('WP_CLI') || !WP_CLI) {
    fwrite(STDERR, "Execute com WP-CLI.\n");
    exit(1);
}

if (!class_exists('WooCommerce')) {
    WP_CLI::error('WooCommerce não está ativo.');
}

@set_time_limit(0);
@ini_set('memory_limit', '512M');

$csv_path = isset($args[0]) ? $args[0] : getenv('MF_CATALOG_CSV');
$dry_run = in_array('dry-run', $args ?? array(), true);
$limit = 0;
foreach ($args ?? array() as $arg) {
    if (strpos((string) $arg, 'limit=') === 0) {
        $limit = max(0, (int) substr($arg, 6));
    }
}

if (!$csv_path || !is_readable($csv_path)) {
    WP_CLI::error('Informe um CSV legível. Exemplo: wp eval-file /scripts/import-catalog.php /scripts/catalogo-amostra.csv');
}

function mf_import_alias($header) {
    $header = strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $header)));
    $map = array(
        'sku pai' => 'sku_pai',
        'sku_pai' => 'sku_pai',
        'sku' => 'sku',
        'tipo' => 'tipo',
        'nome' => 'nome',
        'descricao curta' => 'descricao_curta',
        'descrição curta' => 'descricao_curta',
        'descricao_curta' => 'descricao_curta',
        'descricao' => 'descricao',
        'descrição' => 'descricao',
        'categorias' => 'categorias',
        'colecao' => 'colecao',
        'coleção' => 'colecao',
        'tamanho' => 'tamanho',
        'cor' => 'cor',
        'preco' => 'preco',
        'preço' => 'preco',
        'preco promocional' => 'preco_promocional',
        'preço promocional' => 'preco_promocional',
        'preco_promocional' => 'preco_promocional',
        'estoque' => 'estoque',
        'peso_kg' => 'peso_kg',
        'peso' => 'peso_kg',
        'comprimento_cm' => 'comprimento_cm',
        'largura_cm' => 'largura_cm',
        'altura_cm' => 'altura_cm',
        'imagens' => 'imagens',
        'composicao' => 'composicao',
        'composição' => 'composicao',
        'cuidados' => 'cuidados',
        'tabela_de_medidas' => 'tabela_de_medidas',
        'tabela de medidas' => 'tabela_de_medidas',
        'titulo_seo' => 'titulo_seo',
        'título seo' => 'titulo_seo',
        'descricao_seo' => 'descricao_seo',
        'descrição seo' => 'descricao_seo',
        'destaque' => 'destaque',
    );

    return $map[$header] ?? $header;
}

function mf_import_cell($row, $key) {
    return isset($row[$key]) ? trim((string) $row[$key]) : '';
}

function mf_import_price($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    $value = str_replace(' ', '', $value);
    if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
        $value = str_replace('.', '', $value);
    }
    $value = str_replace(',', '.', $value);
    return is_numeric($value) ? number_format((float) $value, 2, '.', '') : '';
}

function mf_import_attribute($label, $slug) {
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

function mf_import_term($taxonomy, $name, $meta = array()) {
    $name = trim($name);
    if ($name === '') {
        return 0;
    }

    $slug = sanitize_title($name);
    $existing = get_term_by('slug', $slug, $taxonomy);
    if ($existing) {
        $term_id = (int) $existing->term_id;
    } else {
        $created = wp_insert_term($name, $taxonomy, array('slug' => $slug));
        if (is_wp_error($created)) {
            $existing = get_term_by('slug', $slug, $taxonomy);
            if (!$existing) {
                WP_CLI::warning($created->get_error_message() . " ({$taxonomy}: {$name})");
                return 0;
            }
            $term_id = (int) $existing->term_id;
        } else {
            $term_id = (int) $created['term_id'];
        }
    }

    foreach ($meta as $key => $value) {
        if ($value !== '' && $value !== null) {
            update_term_meta($term_id, $key, $value);
        }
    }

    return $term_id;
}

function mf_import_color_hex($name) {
    $known = array(
        'preto' => '#1a1a1a',
        'off-white' => '#f4efe6',
        'branco' => '#f7f7f5',
        'rosa' => '#c48cb3',
        'bege' => '#cbb89a',
        'areia' => '#d8c3a5',
        'marrom' => '#6b4f3a',
        'caramelo' => '#a56b3c',
        'verde' => '#3f6b52',
        'azul-marinho' => '#1d2b44',
        'azul' => '#355c7d',
        'vinho' => '#6e2b3a',
        'terracota' => '#b4634a',
        'oliva' => '#6d6a45',
    );
    $slug = sanitize_title($name);
    if (isset($known[$slug])) {
        return $known[$slug];
    }

    $hash = substr(md5($slug), 0, 6);
    return '#' . $hash;
}

function mf_import_list($value) {
    if ($value === '') {
        return array();
    }
    $parts = preg_split('/\s*[|,;]\s*/', $value);
    $parts = array_filter(array_map('trim', $parts));
    return array_values(array_unique($parts));
}

function mf_import_read_csv($path) {
    $handle = fopen($path, 'rb');
    if (!$handle) {
        WP_CLI::error('Não foi possível abrir o CSV.');
    }

    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        WP_CLI::error('CSV sem cabeçalho.');
    }
    $headers = array_map('mf_import_alias', $headers);

    $required = array('sku', 'nome');
    foreach ($required as $column) {
        if (!in_array($column, $headers, true)) {
            fclose($handle);
            WP_CLI::error("Coluna obrigatória ausente: {$column}");
        }
    }

    $groups = array();
    $line = 1;
    while (($data = fgetcsv($handle)) !== false) {
        $line++;
        if ($data === array(null) || $data === array('')) {
            continue;
        }
        if (count($data) < count($headers)) {
            $data = array_pad($data, count($headers), '');
        }
        $row = array();
        foreach ($headers as $index => $header) {
            $row[$header] = isset($data[$index]) ? trim((string) $data[$index]) : '';
        }
        if (mf_import_cell($row, 'sku') === '' && mf_import_cell($row, 'nome') === '') {
            continue;
        }
        $parent_sku = mf_import_cell($row, 'sku_pai');
        if ($parent_sku === '') {
            $parent_sku = mf_import_cell($row, 'sku');
        }
        $row['_line'] = $line;
        $groups[$parent_sku][] = $row;
    }
    fclose($handle);

    return $groups;
}

function mf_import_category_ids($value) {
    $ids = array();
    foreach (mf_import_list($value) as $name) {
        $term_id = mf_import_term('product_cat', $name);
        if ($term_id) {
            $ids[] = $term_id;
        }
    }
    return $ids;
}

function mf_import_tag_ids($value) {
    $ids = array();
    foreach (mf_import_list($value) as $name) {
        $term_id = mf_import_term('product_tag', $name);
        if ($term_id) {
            $ids[] = $term_id;
        }
    }
    return $ids;
}

function mf_import_save_meta($product_id, $row) {
    $meta = array(
        '_mf_composition' => mf_import_cell($row, 'composicao'),
        '_mf_care_instructions' => mf_import_cell($row, 'cuidados'),
        '_mf_size_guide' => mf_import_cell($row, 'tabela_de_medidas'),
        '_mf_seo_title' => mf_import_cell($row, 'titulo_seo'),
        '_mf_seo_description' => mf_import_cell($row, 'descricao_seo'),
        '_mf_catalog_source' => 'csv',
    );
    foreach ($meta as $key => $value) {
        if ($value !== '') {
            update_post_meta($product_id, $key, wp_kses_post($value));
        }
    }
    if ($meta['_mf_seo_title'] !== '') {
        update_post_meta($product_id, '_yoast_wpseo_title', sanitize_text_field($meta['_mf_seo_title']));
    }
    if ($meta['_mf_seo_description'] !== '') {
        update_post_meta($product_id, '_yoast_wpseo_metadesc', sanitize_text_field($meta['_mf_seo_description']));
    }
}

function mf_import_is_featured($row) {
    $flag = strtolower(mf_import_cell($row, 'destaque'));
    return in_array($flag, array('1', 'sim', 'yes', 'true'), true);
}

$size_taxonomy = mf_import_attribute('Tamanho', 'tamanho');
$color_taxonomy = mf_import_attribute('Cor', 'cor');
$groups = mf_import_read_csv($csv_path);
$total = count($groups);
if ($limit > 0) {
    $groups = array_slice($groups, 0, $limit, true);
}

WP_CLI::log(sprintf(
    '%s %d produto(s) pai de %d no arquivo %s',
    $dry_run ? 'Simulação:' : 'Importando',
    count($groups),
    $total,
    $csv_path
));

wp_defer_term_counting(true);
$created = 0;
$updated = 0;
$variations_saved = 0;
$skipped = 0;
$index = 0;

foreach ($groups as $parent_sku => $rows) {
    $index++;
    $first = $rows[0];
    $type = strtolower(mf_import_cell($first, 'tipo'));
    $is_simple = in_array($type, array('simples', 'simple'), true) && count($rows) === 1 && mf_import_cell($first, 'tamanho') === '' && mf_import_cell($first, 'cor') === '';

    if ($dry_run) {
        WP_CLI::log(sprintf('[%d] %s %s (%d linha(s))', $index, $is_simple ? 'simples' : 'variável', $parent_sku, count($rows)));
        continue;
    }

    $existing_id = wc_get_product_id_by_sku($parent_sku);
    if ($is_simple) {
        $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();
        if ($existing_id && (!$product || !$product->is_type('simple'))) {
            WP_CLI::warning("SKU {$parent_sku} já existe e não é simples. Linha {$first['_line']} ignorada.");
            $skipped++;
            continue;
        }
        $was_new = !$existing_id;
        $product->set_name(mf_import_cell($first, 'nome'));
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        if (!$existing_id) {
            $product->set_sku($parent_sku);
        }
        $product->set_short_description(mf_import_cell($first, 'descricao_curta'));
        $product->set_description(wp_kses_post(mf_import_cell($first, 'descricao')));
        $product->set_category_ids(mf_import_category_ids(mf_import_cell($first, 'categorias')));
        $product->set_tag_ids(mf_import_tag_ids(mf_import_cell($first, 'colecao')));
        $price = mf_import_price(mf_import_cell($first, 'preco'));
        $sale = mf_import_price(mf_import_cell($first, 'preco_promocional'));
        if ($price !== '') {
            $product->set_regular_price($price);
        }
        $product->set_sale_price($sale !== '' ? $sale : '');
        $product->set_manage_stock(true);
        $product->set_stock_quantity(max(0, (int) mf_import_cell($first, 'estoque')));
        $product->set_stock_status(((int) mf_import_cell($first, 'estoque')) > 0 ? 'instock' : 'outofstock');
        if (mf_import_cell($first, 'peso_kg') !== '') {
            $product->set_weight(mf_import_cell($first, 'peso_kg'));
        }
        if (mf_import_is_featured($first)) {
            $product->set_featured(true);
        }
        $product_id = $product->save();
        mf_import_save_meta($product_id, $first);
        $was_new ? $created++ : $updated++;
        continue;
    }

    $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Variable();
    if ($existing_id && (!$product || !$product->is_type('variable'))) {
        WP_CLI::warning("SKU pai {$parent_sku} já existe e não é variável. Linha {$first['_line']} ignorada.");
        $skipped++;
        continue;
    }
    $was_new = !$existing_id;

    $size_ids = array();
    $color_ids = array();
    foreach ($rows as $row) {
        $size_name = mf_import_cell($row, 'tamanho');
        $color_name = mf_import_cell($row, 'cor');
        if ($size_name !== '') {
            $size_ids[sanitize_title($size_name)] = mf_import_term($size_taxonomy, $size_name);
        }
        if ($color_name !== '') {
            $color_ids[sanitize_title($color_name)] = mf_import_term($color_taxonomy, $color_name, array(
                'mf_hex' => mf_import_color_hex($color_name),
            ));
        }
    }

    $product->set_name(mf_import_cell($first, 'nome'));
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    if (!$existing_id) {
        $product->set_sku($parent_sku);
    }
    $product->set_short_description(mf_import_cell($first, 'descricao_curta'));
    $product->set_description(wp_kses_post(mf_import_cell($first, 'descricao')));
    $product->set_category_ids(mf_import_category_ids(mf_import_cell($first, 'categorias')));
    $product->set_tag_ids(mf_import_tag_ids(mf_import_cell($first, 'colecao')));
    if (mf_import_is_featured($first)) {
        $product->set_featured(true);
    }

    $attributes = array();
    if ($size_ids) {
        $attribute = new WC_Product_Attribute();
        $attribute->set_id((int) wc_attribute_taxonomy_id_by_name('tamanho'));
        $attribute->set_name($size_taxonomy);
        $attribute->set_options(array_values(array_filter($size_ids)));
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $attributes[] = $attribute;
    }
    if ($color_ids) {
        $attribute = new WC_Product_Attribute();
        $attribute->set_id((int) wc_attribute_taxonomy_id_by_name('cor'));
        $attribute->set_name($color_taxonomy);
        $attribute->set_options(array_values(array_filter($color_ids)));
        $attribute->set_visible(true);
        $attribute->set_variation(true);
        $attributes[] = $attribute;
    }
    $product->set_attributes($attributes);
    $product_id = $product->save();
    mf_import_save_meta($product_id, $first);

    foreach ($rows as $row) {
        $variation_sku = mf_import_cell($row, 'sku');
        if ($variation_sku === '' || $variation_sku === $parent_sku) {
            WP_CLI::warning("Variação sem SKU próprio na linha {$row['_line']} ({$parent_sku}).");
            $skipped++;
            continue;
        }

        $variation_id = wc_get_product_id_by_sku($variation_sku);
        $variation = $variation_id ? wc_get_product($variation_id) : new WC_Product_Variation();
        if ($variation_id && (!$variation || !$variation->is_type('variation') || (int) $variation->get_parent_id() !== (int) $product_id)) {
            WP_CLI::warning("SKU {$variation_sku} já pertence a outro produto. Linha {$row['_line']} ignorada.");
            $skipped++;
            continue;
        }

        $variation_attributes = array();
        if (mf_import_cell($row, 'tamanho') !== '') {
            $variation_attributes[$size_taxonomy] = sanitize_title(mf_import_cell($row, 'tamanho'));
        }
        if (mf_import_cell($row, 'cor') !== '') {
            $variation_attributes[$color_taxonomy] = sanitize_title(mf_import_cell($row, 'cor'));
        }

        $variation->set_parent_id($product_id);
        $variation->set_status('publish');
        $variation->set_attributes($variation_attributes);
        if (!$variation_id) {
            $variation->set_sku($variation_sku);
        }
        $price = mf_import_price(mf_import_cell($row, 'preco'));
        $sale = mf_import_price(mf_import_cell($row, 'preco_promocional'));
        if ($price !== '') {
            $variation->set_regular_price($price);
        }
        $variation->set_sale_price($sale !== '' ? $sale : '');
        $stock = mf_import_cell($row, 'estoque');
        $variation->set_manage_stock(true);
        $variation->set_stock_quantity($stock === '' ? 0 : max(0, (int) $stock));
        $variation->set_stock_status(((int) $stock) > 0 ? 'instock' : 'outofstock');
        if (mf_import_cell($row, 'peso_kg') !== '') {
            $variation->set_weight(mf_import_cell($row, 'peso_kg'));
        }
        if (mf_import_cell($row, 'comprimento_cm') !== '') {
            $variation->set_length(mf_import_cell($row, 'comprimento_cm'));
        }
        if (mf_import_cell($row, 'largura_cm') !== '') {
            $variation->set_width(mf_import_cell($row, 'largura_cm'));
        }
        if (mf_import_cell($row, 'altura_cm') !== '') {
            $variation->set_height(mf_import_cell($row, 'altura_cm'));
        }
        $variation->save();
        $variations_saved++;
    }

    WC_Product_Variable::sync($product_id);
    wc_delete_product_transients($product_id);
    $was_new ? $created++ : $updated++;

    if ($index % 25 === 0) {
        WP_CLI::log("Progresso: {$index}/" . count($groups) . " produtos pai.");
    }
}

wp_defer_term_counting(false);

if (!$dry_run && function_exists('wc_update_product_lookup_tables')) {
    WP_CLI::log('Atualizando tabelas de consulta do WooCommerce...');
    wc_update_product_lookup_tables();
}

WP_CLI::success(sprintf(
    'Catálogo processado. Criados: %d. Atualizados: %d. Variações salvas: %d. Ignorados: %d.',
    $created,
    $updated,
    $variations_saved,
    $skipped
));
