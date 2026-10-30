# Setup Automático - E-commerce de Moda Feminina (WooCommerce)
# Execute este script após subir o docker-compose

Write-Host "🚀 Iniciando setup do E-commerce de Moda Feminina..." -ForegroundColor Green

# Verificar se Docker está rodando
if (-not (Get-Process -Name "docker" -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Docker não está rodando. Inicie o Docker Desktop primeiro." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Docker detectado. Ambiente será iniciado..." -ForegroundColor Green

# Iniciar containers
docker-compose up -d

Write-Host "`n⏳ Aguardando serviços iniciarem (20s)..." -ForegroundColor Yellow
Start-Sleep -Seconds 20

Write-Host "`n🌐 Acesse o WordPress em: http://localhost:8080" -ForegroundColor Cyan
Write-Host "Credenciais iniciais (após instalação):" -ForegroundColor Cyan
Write-Host "   - Banco: MySQL (root/rootpassword)" -ForegroundColor White
Write-Host "   - Usuário admin: será criado na instalação" -ForegroundColor White
Write-Host "`nApós instalar o WordPress:" -ForegroundColor Magenta
Write-Host "1. Instale e ative o plugin WooCommerce" 
Write-Host "2. Execute o assistente de setup do WooCommerce"
Write-Host "3. Escolha tema 'Storefront' ou 'Hello Elementor'"
Write-Host "4. Crie categorias: Vestidos, Blusas, Calças, Saias, Acessórios, Calçados"
Write-Host "5. Adicione produtos demo de moda feminina"

Write-Host "`n✅ Setup concluído! O ambiente está pronto para desenvolvimento." -ForegroundColor Green
Write-Host "Próximo comando recomendado: ./setup.ps1" -ForegroundColor White
