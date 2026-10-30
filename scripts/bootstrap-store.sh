#!/bin/sh
set -eu

echo "Aguardando os arquivos do WordPress..."
i=0
while [ ! -f /var/www/html/wp-includes/version.php ]; do
  i=$((i + 1))
  if [ "$i" -gt 40 ]; then
    echo "WordPress nao foi copiado para o volume."
    exit 1
  fi
  sleep 3
done

echo "Aguardando o banco..."
i=0
until wp db query "SELECT 1" >/dev/null 2>&1; do
  i=$((i + 1))
  if [ "$i" -gt 40 ]; then
    echo "Banco nao respondeu."
    exit 1
  fi
  sleep 3
done

if ! wp core is-installed >/dev/null 2>&1; then
  wp core install \
    --url="${SITE_URL:-http://localhost:8088}" \
    --title="Moda Feminina" \
    --admin_user="${WP_ADMIN_USER:-admin}" \
    --admin_password="${WP_ADMIN_PASSWORD:-admin-local-only}" \
    --admin_email="${WP_ADMIN_EMAIL:-admin@example.com}" \
    --skip-email
fi

wp language core install pt_BR --activate
wp plugin install woocommerce --activate
wp language plugin install woocommerce pt_BR || true
wp plugin install woocommerce-extra-checkout-fields-for-brazil --activate
wp plugin install woocommerce-mercadopago
wp theme install storefront
wp theme activate moda-feminina
wp rewrite structure '/%postname%/' --hard
wp option update woocommerce_currency BRL
wp option update woocommerce_currency_pos left
wp option update woocommerce_price_thousand_sep "."
wp option update woocommerce_price_decimal_sep ","
wp option update woocommerce_default_country "BR:SP"
wp option update blogdescription "Loja de moda feminina ? ambiente local"
wp wc tool run install_pages --user=1
wp post update "$(wp option get woocommerce_shop_page_id)" --post_name=loja --post_title="Loja"
wp post update "$(wp option get woocommerce_cart_page_id)" --post_name=carrinho --post_title="Carrinho"
wp post update "$(wp option get woocommerce_checkout_page_id)" --post_name=finalizar-compra --post_title="Finalizar compra"
wp post update "$(wp option get woocommerce_myaccount_page_id)" --post_name=minha-conta --post_title="Minha conta"
wp rewrite flush --hard
wp eval-file /scripts/seed-catalog.php --user=1
wp option update woocommerce_attribute_lookup_enabled yes
wp wc tool run regenerate_product_attributes_lookup_table --user=1
wp action-scheduler run --batches=20

echo "Loja local pronta em ${SITE_URL:-http://localhost:8088}"
echo "Admin: ${WP_ADMIN_USER:-admin}"
