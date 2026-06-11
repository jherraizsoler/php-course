<?php
/**
 * Visor 3D / volumétrico — front controller.
 *
 * Rutas (?action=):
 *   lista     (def.)  catálogo de modelos (galería)
 *   subir            GET = formulario · POST = valida (CSRF + extensión + tamaño) y guarda
 *   ver  &id=        visor del modelo (Three.js para mallas, vtk.js para volumétrico)
 *   archivo &id=     sirve el binario del modelo (lo consumen los loaders JS)
 *   eliminar &id=    POST con CSRF: borra registro + archivo
 *
 * PHP = backend (subida, catálogo en BBDD, servir el modelo). El render 3D
 * ocurre en el navegador con Three.js / vtk.js.
 */

declare(strict_types=1);
session_start();

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/ModeloRepository.php';

$cfg = require __DIR__ . '/config.php';

try {
    $repo = new ModeloRepository();
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo conectar a MySQL. ¿Arrancaste MAMP (o Docker) y creaste la BBDD '
        . "'curso_3d' con tools/db-setup.php? Detalle: " . e($e->getMessage()));
}

$action = $_GET['action'] ?? 'lista';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* ---------------------------------------------------------------- SERVIR THUMBNAIL
 * Sirve la miniatura JPEG/PNG generada por el visor (no necesita autenticación,
 * es una imagen derivada del propio modelo). */
if ($action === 'thumb') {
    $m = $repo->buscar($id);
    if (! $m || empty($m['thumbnail'])) { http_response_code(404); exit; }
    $ruta = $cfg['uploads_dir'] . '/' . basename((string) $m['thumbnail']);
    if (! is_file($ruta)) { http_response_code(404); exit; }
    $ext  = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
    $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=86400');
    header('Content-Length: ' . (string) filesize($ruta));
    readfile($ruta);
    exit;
}

/* ---------------------------------------------------------------- GUARDAR THUMBNAIL
 * Recibe un dataURL (JPEG) desde el canvas del visor y lo persiste como miniatura.
 * Llamado vía fetch() tras el primer render completo del modelo. */
if ($action === 'thumbnail' && $method === 'POST') {
    header('Content-Type: application/json');
    $m = $repo->buscar($id);
    if (! $m || ! csrf_ok($_POST['csrf'] ?? '')) { echo '{"ok":false}'; exit; }
    $data = $_POST['data'] ?? '';
    if (strlen($data) > 400_000) { echo '{"ok":false}'; exit; }   // max ~270 KB imagen
    if (! preg_match('/^data:image\/(jpeg|png|webp);base64,/', $data, $mt)) {
        echo '{"ok":false}'; exit;
    }
    $b64 = (string) preg_replace('/^data:image\/[a-z]+;base64,/', '', $data);
    $bin = base64_decode($b64, true);
    if ($bin === false || strlen($bin) < 100) { echo '{"ok":false}'; exit; }
    $ext   = $mt[1] === 'jpeg' ? 'jpg' : $mt[1];
    $fname = 'thumb_' . $id . '.' . $ext;
    // Eliminar miniatura anterior si tiene distinto nombre.
    if (! empty($m['thumbnail']) && $m['thumbnail'] !== $fname) {
        $old = $cfg['uploads_dir'] . '/' . basename((string) $m['thumbnail']);
        if (is_file($old)) @unlink($old);
    }
    if (@file_put_contents($cfg['uploads_dir'] . '/' . $fname, $bin) !== false) {
        $repo->guardarThumbnail($id, $fname);
        echo '{"ok":true}';
    } else {
        echo '{"ok":false}';
    }
    exit;
}

/* ---------------------------------------------------------------- SERVIR ARCHIVO
 * Va lo primero: escribe bytes crudos, sin layout. Solo sirve archivos que
 * estén en el catálogo (id → nombre en BBDD), así no se puede pedir una ruta
 * arbitraria del servidor (anti path traversal). */
if ($action === 'archivo') {
    $m = $repo->buscar($id);
    if (! $m) { http_response_code(404); exit('Modelo no encontrado.'); }

    $ruta = $cfg['uploads_dir'] . '/' . basename($m['archivo']);
    if (! is_file($ruta)) { http_response_code(404); exit('Archivo no disponible.'); }

    [$tipo, $mime] = $cfg['formatos'][$m['formato']] ?? ['malla', 'application/octet-stream'];
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) filesize($ruta));
    header('Content-Disposition: inline; filename="' . basename($m['nombre_orig']) . '"');
    header('X-Content-Type-Options: nosniff');
    readfile($ruta);
    exit;
}

/* ---------------------------------------------------------------- ELIMINAR */
if ($action === 'eliminar') {
    if ($method !== 'POST' || ! csrf_ok($_POST['csrf'] ?? '')) {
        http_response_code(400); exit('Petición inválida.');
    }
    if ($m = $repo->buscar($id)) {
        $ruta = $cfg['uploads_dir'] . '/' . basename($m['archivo']);
        if (is_file($ruta)) @unlink($ruta);
        // Eliminar miniatura asociada si existe.
        if (! empty($m['thumbnail'])) {
            $thumbRuta = $cfg['uploads_dir'] . '/' . basename((string) $m['thumbnail']);
            if (is_file($thumbRuta)) @unlink($thumbRuta);
        }
        $repo->eliminar($id);
    }
    header('Location: index.php');
    exit;
}

/* ---------------------------------------------------------------- SUBIR */
if ($action === 'subir') {
    $error = '';
    if ($method === 'POST') {
        $error = procesar_subida($repo, $cfg);
        if ($error === '') { header('Location: index.php'); exit; }
    }
    ob_start();
    $sectores = $cfg['sectores'];
    $exts     = implode(', ', array_map(fn($e) => '.' . $e, array_keys($cfg['formatos'])));
    $maxMb    = (int) ($cfg['max_bytes'] / 1024 / 1024);
    require __DIR__ . '/views/subir.php';
    layout('Subir modelo', ob_get_clean());
    exit;
}

/* ---------------------------------------------------------------- USOS POR SECTOR */
if ($action === 'usos') {
    $sectoresFull = $cfg['sectores_full'];
    $formatos     = $cfg['formatos'];
    ob_start();
    require __DIR__ . '/views/usos.php';
    layout('Usos por sector', ob_get_clean());
    exit;
}

/* ---------------------------------------------------------------- VER (visor) */
if ($action === 'ver') {
    $m = $repo->buscar($id);
    if (! $m) { http_response_code(404); exit('Modelo no encontrado.'); }

    [$tipo] = $cfg['formatos'][$m['formato']] ?? ['malla'];
    $sectores = $cfg['sectores'];
    $ficha    = $cfg['sectores_full'][$m['sector']] ?? $cfg['sectores_full']['otro'];

    $ruta       = $cfg['uploads_dir'] . '/' . basename($m['archivo']);
    $fileExists = is_file($ruta);

    // importmap de Three.js solo cuando hace falta (mallas). vtk.js se carga
    // como UMD dentro de la vista volumétrica.
    $extraHead = $tipo === 'malla' ? <<<HTML
    <script type="importmap">
    {
      "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
        "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
      }
    }
    </script>
    HTML : '';

    ob_start();
    require __DIR__ . '/views/visor.php';
    layout('Visor · ' . $m['nombre'], ob_get_clean(), $extraHead);
    exit;
}

/* ---------------------------------------------------------------- CATÁLOGO (def.) */
$modelos      = $repo->todos();
$sectores     = $cfg['sectores'];
$sectoresFull = $cfg['sectores_full'];
$formatos     = $cfg['formatos'];
$uploadsDir   = $cfg['uploads_dir'];
ob_start();
require __DIR__ . '/views/catalogo.php';
layout('Catálogo', ob_get_clean());


/* ================================================================ LÓGICA SUBIDA */

/**
 * Valida y guarda un archivo subido. Devuelve '' si OK, o el mensaje de error.
 * Defensas: CSRF, tamaño máximo, whitelist de extensiones, nombre de destino
 * generado por el servidor (nunca el del usuario).
 */
function procesar_subida(ModeloRepository $repo, array $cfg): string
{
    if (! csrf_ok($_POST['csrf'] ?? '')) {
        return 'Token CSRF inválido. Recarga la página.';
    }

    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $desc   = trim((string) ($_POST['descripcion'] ?? ''));
    $fuente = trim((string) ($_POST['fuente'] ?? ''));
    $sector = (string) ($_POST['sector'] ?? 'otro');
    if ($nombre === '')                              return 'El nombre es obligatorio.';
    if (! isset($cfg['sectores'][$sector]))          $sector = 'otro';
    // Fuente opcional: si se da, debe ser una URL válida.
    if ($fuente !== '' && ! filter_var($fuente, FILTER_VALIDATE_URL)) {
        return 'La fuente, si se indica, debe ser una URL válida (http/https).';
    }

    $f = $_FILES['modelo'] ?? null;
    if (! $f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ($f['error'] ?? 0) === UPLOAD_ERR_INI_SIZE
            ? 'El archivo supera el límite del servidor (upload_max_filesize).'
            : 'Selecciona un archivo válido.';
    }
    if ($f['size'] > $cfg['max_bytes']) {
        return 'El archivo supera el máximo permitido (' . (int)($cfg['max_bytes']/1024/1024) . ' MB).';
    }

    $ext = strtolower(pathinfo((string) $f['name'], PATHINFO_EXTENSION));
    if (! isset($cfg['formatos'][$ext])) {
        return 'Formato no soportado. Permitidos: ' . implode(', ', array_keys($cfg['formatos'])) . '.';
    }

    // Asegura que existe la carpeta de subidas.
    if (! is_dir($cfg['uploads_dir']) && ! @mkdir($cfg['uploads_dir'], 0775, true)) {
        return 'No se pudo crear la carpeta de subidas.';
    }

    // Nombre de destino generado por el servidor (evita colisiones y nombres maliciosos).
    $destino = bin2hex(random_bytes(8)) . '.' . $ext;
    if (! move_uploaded_file($f['tmp_name'], $cfg['uploads_dir'] . '/' . $destino)) {
        return 'No se pudo guardar el archivo subido.';
    }

    $repo->crear([
        'nombre'      => mb_substr($nombre, 0, 150),
        'descripcion' => $desc !== '' ? mb_substr($desc, 0, 500) : null,
        'fuente'      => $fuente !== '' ? mb_substr($fuente, 0, 255) : null,
        'formato'     => $ext,
        'sector'      => $sector,
        'archivo'     => $destino,
        'nombre_orig' => mb_substr((string) $f['name'], 0, 255),
        'bytes'       => (int) $f['size'],
    ]);
    return '';
}
