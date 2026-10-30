-- Script de configuração inicial do WooCommerce para E-commerce de Moda Feminina
-- Execute após a instalação do WordPress e WooCommerce

-- 1. Criar categorias principais de moda feminina
INSERT INTO wp_terms (name, slug, term_group) VALUES 
('Vestidos', 'vestidos', 0),
('Blusas', 'blusas', 0),
('Calças', 'calcas', 0),
('Saias', 'saias', 0),
('Acessórios', 'acessorios', 0),
('Calçados', 'calcados', 0),
('Lingerie', 'lingerie', 0),
('Nova Coleção', 'nova-colecao', 0);

-- Associar categorias como termos de taxonomia 'product_cat'
INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count)
SELECT term_id, 'product_cat', '', 0, 0 FROM wp_terms 
WHERE slug IN ('vestidos','blusas','calcas','saias','acessorios','calcados','lingerie','nova-colecao');

-- 2. Configurações básicas do WooCommerce
UPDATE wp_options SET option_value = 'yes' WHERE option_name = 'woocommerce_calc_taxes';
UPDATE wp_options SET option_value = 'yes' WHERE option_name = 'woocommerce_ship_to_countries';
UPDATE wp_options SET option_value = 'BR' WHERE option_name = 'woocommerce_default_country';

-- 3. Ativar página de loja, carrinho, checkout e minha conta (será criado pelo assistente, mas garantimos slugs)
UPDATE wp_options SET option_value = (SELECT ID FROM wp_posts WHERE post_name = 'loja' LIMIT 1) 
WHERE option_name = 'woocommerce_shop_page_id';

-- Nota: Este script é um ponto de partida. O ideal é usar o assistente do WooCommerce na primeira execução.
-- Após rodar o assistente, importe o CSV de produtos demo (products-demo.csv).
