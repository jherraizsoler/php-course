#!/usr/bin/env bash
# =============================================================================
#  PHP Course · setup.sh
#  Deja el proyecto listo tras clonarlo: dependencias (Composer), base de datos
#  y .env de la demo de seguridad. (Linux/macOS, o Git Bash en Windows.)
#
#  Uso:   bash setup.sh
#  Otro puerto MySQL:  DB_PORT=3306 bash setup.sh
# =============================================================================
set -u
ROOT="$(cd "$(dirname "$0")" && pwd)"
echo "== PHP Course · puesta en marcha =="

# 1) Localizar PHP
PHP="$(command -v php || true)"
if [ -z "$PHP" ]; then
    PHP="$(ls /c/MAMP/bin/php/php8.3*/php.exe 2>/dev/null | tail -1 || true)"
fi
[ -z "$PHP" ] && { echo "❌ No encuentro PHP. Instala MAMP/XAMPP o añade php al PATH."; exit 1; }
echo "PHP: $PHP"

# 2) Localizar Composer
run_composer() {
    ( cd "$1" || return
      if command -v composer >/dev/null 2>&1; then composer install --no-interaction --no-dev
      elif [ -f /c/composer/composer.phar ]; then "$PHP" /c/composer/composer.phar install --no-interaction --no-dev
      else echo "  ⚠️  Composer no encontrado; me salto $1"; fi )
}

# 3) composer install en cada sub-proyecto con composer.json
echo ""; echo "[1/3] Instalando dependencias (Composer)..."
while IFS= read -r f; do
    dir="$(dirname "$f")"
    echo "  → $dir"
    run_composer "$dir"
done < <(find "$ROOT" -name composer.json -not -path '*/vendor/*')

# 4) Base de datos
echo ""; echo "[2/3] Configurando base de datos..."
"$PHP" "$ROOT/tools/db-setup.php"

# 5) .env de la demo de seguridad
echo ""; echo "[3/3] Preparando .env de login-seguro..."
"$PHP" "$ROOT/tools/env-setup.php"

echo ""; echo "✅ Todo listo. Arranca MAMP/XAMPP y abre el proyecto en el navegador."
