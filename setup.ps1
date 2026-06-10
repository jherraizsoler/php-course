# =============================================================================
#  PHP Course · setup.ps1
#  Deja el proyecto listo tras clonarlo: dependencias (Composer), base de datos
#  y .env de la demo de seguridad. Pensado para Windows + MAMP/XAMPP.
#
#  Uso:   .\setup.ps1
#  Con otro puerto MySQL (ej. XAMPP 3306):  $env:DB_PORT='3306'; .\setup.ps1
# =============================================================================
$ErrorActionPreference = 'Stop'
Write-Host "== PHP Course · puesta en marcha ==" -ForegroundColor Cyan

# 1) Localizar PHP -----------------------------------------------------------
$php = (Get-Command php -ErrorAction SilentlyContinue).Source
if (-not $php) {
    $cand = Get-ChildItem "C:\MAMP\bin\php\php8.3*\php.exe","C:\xampp\php\php.exe" -ErrorAction SilentlyContinue |
            Select-Object -Last 1
    if ($cand) { $php = $cand.FullName }
}
if (-not $php) { throw "No encuentro PHP. Instala MAMP/XAMPP o añade 'php' al PATH." }
Write-Host "PHP: $php"

# 2) Localizar Composer ------------------------------------------------------
$composer = (Get-Command composer -ErrorAction SilentlyContinue).Source
$composerPhar = @("C:\composer\composer.phar","$env:USERPROFILE\composer.phar") |
                Where-Object { Test-Path $_ } | Select-Object -First 1

function Invoke-Composer($dir) {
    Push-Location $dir
    try {
        if ($composer)          { & $composer install --no-interaction --no-dev }
        elseif ($composerPhar)  { & $php $composerPhar install --no-interaction --no-dev }
        else { Write-Warning "Composer no encontrado; me salto $dir (instálalo desde getcomposer.org)" }
    } finally { Pop-Location }
}

# 3) composer install en cada sub-proyecto con composer.json -----------------
Write-Host "`n[1/3] Instalando dependencias (Composer)..." -ForegroundColor Yellow
Get-ChildItem -Recurse -Filter composer.json -ErrorAction SilentlyContinue |
    Where-Object { $_.FullName -notmatch '\\vendor\\' } |
    ForEach-Object {
        Write-Host "  → $($_.Directory.FullName)"
        Invoke-Composer $_.Directory.FullName
    }

# 4) Base de datos -----------------------------------------------------------
Write-Host "`n[2/3] Configurando base de datos..." -ForegroundColor Yellow
& $php (Join-Path $PSScriptRoot "tools\db-setup.php")

# 5) .env de la demo de seguridad -------------------------------------------
Write-Host "`n[3/3] Preparando .env de login-seguro..." -ForegroundColor Yellow
& $php (Join-Path $PSScriptRoot "tools\env-setup.php")

Write-Host "`n✅ Todo listo. Arranca MAMP/XAMPP y abre el proyecto en el navegador." -ForegroundColor Green
Write-Host "   Portada:  http://localhost:8888/repoPersonales/php-course/  (ajusta puerto/ruta a tu servidor)" -ForegroundColor DarkGray
