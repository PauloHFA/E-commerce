# E-commerce de Moda Feminina (WooCommerce)

Projeto Freelancer: Loja online especializada em roupas e acessórios femininos, construída com **WordPress + WooCommerce**.

## Visão Geral
Este projeto implementa todos os requisitos do documento "Projeto Freelancer E-commerce de Moda Feminina (WooCommerce) – Requisitos e Delimitação".

**Tecnologias principais:**
- WordPress + WooCommerce (core)
- MySQL 8
- Docker Compose (ambiente local)
- Tema customizável (Storefront ou Elementor)
- PHP 8.2+, HTML5, CSS3, JavaScript

## Funcionalidades Principais (conforme PDF)
- Catálogo completo de produtos de moda feminina com variações (tamanho, cor)
- Filtros avançados e busca
- Carrinho de compras e checkout otimizado
- Sistema de contas de cliente e histórico de pedidos
- Integração de pagamento (Pix, cartão, boleto)
- Gestão administrativa completa
- Design responsivo e atrativo focado em moda

## Como executar (ambiente local)

1. Abra o Docker Desktop e espere o engine ficar verde.
2. Na pasta do projeto:
   ```powershell
   .\setup.ps1
   ```
3. O script instala WordPress, WooCommerce, campos brasileiros de checkout, o tema filho e dois produtos variáveis de exemplo.
4. Abra http://localhost:8088. O admin local está no `.env` (`WP_ADMIN_USER` / `WP_ADMIN_PASSWORD`). A porta 8080 deste computador já está ocupada.

Isso ainda não é a loja do PDF. Faltam a planilha dos 300 produtos, Mercado Pago, frete, SEO e Instagram Shopping. O modelo da planilha está em [docs/modelo-planilha-produtos.csv](docs/modelo-planilha-produtos.csv).

## Estrutura do Projeto
- `/docs` - Documentação e plano detalhado
- `/frontend` - Customizações de tema, CSS e JavaScript
- `/backend` - Plugins custom e configurações PHP
- `docker-compose.yml` - Ambiente completo (WordPress + MySQL)
- `setup.ps1` - Script de inicialização automática

## Plano de Implementação
Veja o plano completo e atualizado em [docs/plano-implementacao.md](docs/plano-implementacao.md).

**Status atual: a loja não está pronta.** O PDF pede catálogo de 300+ produtos variáveis, Mercado Pago, frete, cupons, SEO e Instagram Shopping. Nada disso foi instalado nem testado.

O que existe hoje é um esqueleto: Docker Compose, rascunho de tema filho e 4 produtos de exemplo. Detalhe em [docs/status-requisitos.md](docs/status-requisitos.md).

Não execute `woocommerce-setup.sql` — ele pode corromper o banco.

---
*Implementado com Copilot SDK em VS Code - 30/10/2026*

