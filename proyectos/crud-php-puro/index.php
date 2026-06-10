<?php
/**
 * Front controller: punto de entrada único.
 * Enruta según ?action= y delega en el repositorio (modelo) y las vistas.
 *
 * Es, en pequeño, lo que hace CodeIgniter por ti automáticamente.
 */

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/TareaRepository.php';

try {
    $repo = new TareaRepository();
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo conectar a MySQL. ¿Arrancaste MAMP e importaste schema.sql? '
        . 'Detalle: ' . e($e->getMessage()));
}

$action = $_GET['action'] ?? 'lista';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

switch ($action) {

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo'] ?? '');
            if ($titulo === '') {
                render('form', ['error' => 'El título es obligatorio.', 'tarea' => null]);
                break;
            }
            $repo->crear($titulo);
            header('Location: index.php');
            exit;
        }
        render('form', ['error' => null, 'tarea' => null]);
        break;

    case 'editar':
        $tarea = $repo->buscar($id);
        if (!$tarea) {
            http_response_code(404);
            exit('Tarea no encontrada');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo'] ?? '');
            if ($titulo === '') {
                render('form', ['error' => 'El título es obligatorio.', 'tarea' => $tarea]);
                break;
            }
            $repo->actualizarTitulo($id, $titulo);
            header('Location: index.php');
            exit;
        }
        render('form', ['error' => null, 'tarea' => $tarea]);
        break;

    case 'completar':
        $repo->alternarCompletada($id);
        header('Location: index.php');
        exit;

    case 'eliminar':
        $repo->eliminar($id);
        header('Location: index.php');
        exit;

    case 'lista':
    default:
        render('lista', ['tareas' => $repo->todas()]);
        break;
}
