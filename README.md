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

## Como Executar (Ambiente Local)

1. Copie o arquivo de exemplo:
   ```powershell
   Copy-Item .env.example .env
   ```
2. Inicie o ambiente:
   ```powershell
   ./setup.ps1
   ```
3. Acesse **http://localhost:8080** e complete a instalação do WordPress
4. Ative o plugin **WooCommerce** e execute o assistente de configuração

## Estrutura do Projeto
- `/docs` - Documentação e plano detalhado
- `/frontend` - Customizações de tema, CSS e JavaScript
- `/backend` - Plugins custom e configurações PHP
- `docker-compose.yml` - Ambiente completo (WordPress + MySQL)
- `setup.ps1` - Script de inicialização automática

## Plano de Implementação
Veja o plano completo e atualizado em [docs/plano-implementacao.md](docs/plano-implementacao.md).

**Status atual:** Infraestrutura Docker + scripts de setup criados. O ambiente local está pronto para uso. Próxima etapa: configuração automática do WooCommerce e tema customizado com identidade visual feminina.

---
*Implementado com Copilot SDK em VS Code - 30/10/2026*

