# E-commerce de Moda Feminina (WooCommerce)

Projeto Freelancer: Loja online especializada em roupas e acessórios femininos, construída com **WordPress + WooCommerce**.

## Visão Geral
Este projeto implementa todos os requisitos do documento "Projeto Freelancer E-commerce de Moda Feminina (WooCommerce) – Requisitos e Delimitação".

**Tecnologias principais:**
- WordPress + WooCommerce (core)
- MySQL 8
- Docker Compose (ambiente local)
- Tema customizável (Storefront child theme)
- PHP 8.2+, HTML5, CSS3, JavaScript (ES6+)

## ✅ Redesign Premium Completo (02/11/2026)

O layout foi completamente redesenhado com estética **premium/fashion** inspirada em marcas como **Zara, Amaro, Farm**:

### Design System
- **Paleta neutra/premium**: Preto, cinzas quentes, champagne gold, off-white
- **Tipografia**: Playfair Display (serif headings) + Source Sans 3 (body) + JetBrains Mono (code)
- **Espaçamento**: Escala 4px base com tipografia fluida (clamp)
- **Sombras**: Camadas sutis (xs → 2xl) com baixa opacidade
- **Transições**: Cubic-bezier (0.4, 0, 0.2, 1) para easing natural
- **CSS Cascade Layers**: @layer base, components, utilities

### Componentes Implementados

| Componente | Arquivo | Status |
|------------|---------|--------|
| Barra de anúncios fixa | `header.php` | ✅ |
| Header premium (logo serif, busca, ações) | `header.php` | ✅ |
| Navegação sticky uppercase | `header.php` | ✅ |
| Hero banner cinematográfico | `front-page.php` | ✅ |
| Categorias em destaque (grid) | `front-page.php` | ✅ |
| Vitrine de produtos | `front-page.php` + `content-product.php` | ✅ |
| Newsletter com benefícios | `front-page.php` | ✅ |
| Instagram feed placeholder | `front-page.php` | ✅ |
| Footer premium (brand, nav, social, pagamentos) | `footer.php` | ✅ |
| Mini-cart drawer (slide-in) | `footer.php` + `mini-cart.php` | ✅ |
| Product cards premium | `content-product.php` | ✅ |

### JavaScript Interactions (`js/custom.js`)
- Announcement bar dismiss (sessionStorage)
- Header scroll effect (backdrop blur + shadow)
- Search focus states
- Mobile menu toggle com focus trap
- Mini-cart drawer com AJAX updates
- AJAX add-to-cart (produtos simples + variáveis)
- Quantity buttons, filter sidebar, color swatches
- Product gallery thumbnail switching
- Sticky add-to-cart, infinite scroll, smooth scroll
- IntersectionObserver scroll animations com stagger
- Wishlist com localStorage persistence
- Toast notifications (success/error/warning/info)
- Loading skeletons, hero scroll button

### AJAX Endpoints (`functions.php`)
- `moda_feminina_add_to_cart` — simples e variáveis
- `moda_feminina_load_more_products` — infinite scroll
- `moda_feminina_toggle_wishlist` — persistência
- Cart fragments para mini-cart updates

## Funcionalidades Principais (conforme PDF)
- Catálogo completo de produtos de moda feminina com variações (tamanho, cor)
- Filtros avançados e busca
- Carrinho de compras e checkout otimizado
- Sistema de contas de cliente e histórico de pedidos
- Integração de pagamento (Pix, cartão, boleto)
- Gestão administrativa completa
- **Design responsivo e atrativo focado em moda** ✅ **IMPLEMENTADO**

## Como executar (ambiente local)

1. Abra o Docker Desktop e espere o engine ficar verde.
2. Na pasta do projeto:
   ```powershell
   .\setup.ps1
   ```
3. O script instala WordPress, WooCommerce, campos brasileiros de checkout, o tema filho e dois produtos variáveis de exemplo.
4. Abra http://localhost:8088. O admin local está no `.env` (`WP_ADMIN_USER` / `WP_ADMIN_PASSWORD`). A porta 8080 deste computador já está ocupada.

A vitrine local tem página inicial redesenhada, produtos variáveis e filtros de tamanho, cor e preço. O plugin do Mercado Pago fica instalado e desativado até existirem as credenciais.

## Estrutura do Projeto
- `/docs` - Documentação e plano detalhado
- `/frontend` - Tema filho completo (Storefront child)
  - `style.css` — Design system + todos os componentes (94KB)
  - `header.php` — Header premium + announcement bar + nav
  - `footer.php` — Footer premium + mini-cart drawer + overlay
  - `front-page.php` — Homepage com hero, collections, products, newsletter, instagram, CTA
  - `functions.php` — Assets, AJAX endpoints, cart fragments, wishlist
  - `js/custom.js` — Todas as interações premium (32KB)
  - `woocommerce/` — Templates WooCommerce customizados
- `/backend` - Plugins custom e configurações PHP
- `docker-compose.yml` - Ambiente completo (WordPress + MySQL)
- `setup.ps1` - Script de inicialização automática

## Plano de Implementação
Veja o plano completo e atualizado em [docs/plano-implementacao.md](docs/plano-implementacao.md).

**Status atual:** 
- ✅ **Redesign visual premium completo** (layout, tipografia, cores, componentes, interações)
- ✅ **Tema filho funcional** com todos os templates WooCommerce
- ✅ **JavaScript modular** com todas as interações premium
- ⏳ Catálogo de 300+ produtos variáveis (planilha em `docs/modelo-planilha-produtos.csv`)
- ⏳ Pagamento real (Mercado Pago - aguarda credenciais)
- ⏳ Frete, cupons, SEO, Instagram Shopping

O modelo da planilha está em [docs/modelo-planilha-produtos.csv](docs/modelo-planilha-produtos.csv).

## Próximos Passos Sugeridos
1. **Adicionar fotos reais** no hero, categorias e produtos (substituir placeholders)
2. **Ajustar textos** para match exato do spec (ex: "NOVA COLEÇÃO / primavera • verão")
3. **Implementar thumbnails circulares** nas categorias
4. **Adicionar botão "Espiar"** (quick view modal) nos produtos
5. **Testar em mobile** (DevTools device toolbar)
6. **Validar acessibilidade** (axe, Lighthouse)
7. **Importar catálogo** de 300+ produtos via planilha

---
*Redesign premium implementado com Copilot SDK em VS Code - 02/11/2026*

