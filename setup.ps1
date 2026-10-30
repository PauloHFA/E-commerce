# Sobe WordPress + WooCommerce e aplica o catalogo de exemplo.
# Senha local fica no .env. Nao use estas credenciais em producao.

$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

Write-Host "Iniciando a loja local de moda feminina..." -ForegroundColor Green

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "Criei .env a partir do exemplo. Troque WP_ADMIN_PASSWORD antes de expor o site." -ForegroundColor Yellow
}

$dockerReady = $false
for ($i = 0; $i -lt 24; $i++) {
    docker info *> $null
    if ($LASTEXITCODE -eq 0) {
        $dockerReady = $true
        break
    }

    if ($i -eq 0) {
        $desktop = Join-Path $env:ProgramFiles "Docker\Docker\Docker Desktop.exe"
        if (Test-Path $desktop) {
            Start-Process -FilePath $desktop
            Write-Host "Docker Desktop aberto. Aguardando o daemon (ate 2 minutos)..." -ForegroundColor Yellow
        }
    }

    Start-Sleep -Seconds 5
}

if (-not $dockerReady) {
    Write-Host "O Docker nao respondeu. Abra o Docker Desktop e execute .\setup.ps1 de novo." -ForegroundColor Red
    exit 1
}

docker compose up -d
if ($LASTEXITCODE -ne 0) {
    exit $LASTEXITCODE
}

docker compose --profile tools run --rm --entrypoint sh wpcli /scripts/bootstrap-store.sh
exit $LASTEXITCODE
