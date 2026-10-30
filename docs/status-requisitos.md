# Status real dos requisitos

Fonte: [requisitos-extraidos.txt](requisitos-extraidos.txt) (27 páginas do PDF do freelancer).

**Conclusão: a loja não está implementada.** O que existe é um esqueleto de repositório. Nada foi instalado, testado ou publicado.

## O que o PDF pede (obrigatório, prioridade M)

| Área | Requisitos | Status |
| --- | --- | --- |
| WordPress + WooCommerce em homologação e produção | seção 3 e 5 | Não iniciado. Docker local existe, mas o daemon não está rodando e o tema não é montado no container |
| Catálogo de 300+ produtos variáveis (tamanho e cor) | RF-CAT-01, RF-CAT-02 | Não. Há 4 produtos simples de exemplo, sem preço, sem variação e sem planilha do cliente |
| Página de produto (galeria, composição, medidas) | RF-CAT-03 | Não |
| Filtros combináveis de tamanho, cor e preço | RF-FLT-01 | Não |
| Carrinho, mini carrinho, cupom, frete por CEP | RF-CRT-01 a 03 | Não (só o que o WooCommerce traria depois de instalado) |
| Checkout brasileiro (CPF/CNPJ, CEP) | RF-CHK-02 | Não |
| Mercado Pago: Pix, cartão e boleto + webhooks | RF-PAG-01 a 04 | Não. Depende de conta verificada do cliente |
| Frete Correios / Melhor Envio / Frenet | RF-FRT-01 | Não. Depende de contrato do cliente |
| Cupons | RF-CUP-01 | Não |
| Painel, estoque, perfis | RF-ADM-01 a 04 | Não |
| E-mails transacionais via SMTP | RF-EML-01 | Não |
| Páginas institucionais e legais | RF-DSG-02, RF-LGL-01 | Não. Textos dependem do cliente e de revisão jurídica |
| SEO técnico | RF-SEO-01 | Não |
| Instagram Shopping | RF-IGS-01 | Não. Depende de Business Manager da Meta |
| LGPD, backups, 2FA, segurança | RNF-03, RNF-05, RNF-13 | Não |

## O que existe no repositório

- `docker-compose.yml`: WordPress + MySQL. Não sobe enquanto o Docker Desktop estiver parado. O tema em `frontend/` **não** é montado no container.
- `frontend/style.css` e `frontend/js/custom.js`: rascunho visual. Não é uma loja.
- `frontend/functions.php`: tema filho mínimo (assets, galeria, texto do botão).
- `products-demo.csv`: 4 linhas de exemplo. **Não** é o modelo da seção 8.2 e não tem preço nem variações.
- `woocommerce-setup.sql`: **não usar.** Insere termos sem `term_id` e pode corromper o banco. Categorias devem ser criadas pelo WooCommerce ou WP-CLI.

## O que este repositório não pode inventar

O PDF deixa explícito que fotos, textos, planilha dos 300 produtos, CNPJ, conta Mercado Pago, contrato de frete e ativos da Meta são do cliente. Sem isso, catálogo real, pagamento real e Instagram Shopping ficam bloqueados.

## O que já sobe localmente

`.\setup.ps1` instala WordPress, WooCommerce, campos brasileiros, o tema filho e dois produtos variáveis. A loja local está em http://localhost:8088/loja/.

Também já existe:
- página inicial com produtos em destaque
- filtros combináveis de tamanho, cor (amostra) e faixa de preço
- plugin do Mercado Pago instalado e **desativado** (ativar só com as credenciais do cliente)

Ainda não entrega catálogo de 300 produtos, pagamento real, frete, SEO nem Instagram Shopping.
