<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Acceso a la tabla `modelos` (catálogo). Consultas preparadas siempre.
 * Mismo espíritu que TareaRepository del CRUD en PHP puro.
 */
final class ModeloRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conexion();
    }

    /** @return array<int,array<string,mixed>> */
    public function todos(): array
    {
        return $this->db->query(
            'SELECT * FROM modelos ORDER BY subido_en DESC, id DESC'
        )->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public function buscar(int $id): ?array
    {
        $st = $this->db->prepare('SELECT * FROM modelos WHERE id = ?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function crear(array $m): int
    {
        $st = $this->db->prepare(
            'INSERT INTO modelos (nombre, descripcion, fuente, formato, sector, archivo, nombre_orig, bytes)
             VALUES (:nombre, :descripcion, :fuente, :formato, :sector, :archivo, :nombre_orig, :bytes)'
        );
        $st->execute([
            ':nombre'      => $m['nombre'],
            ':descripcion' => $m['descripcion'],
            ':fuente'      => $m['fuente'] ?? null,
            ':formato'     => $m['formato'],
            ':sector'      => $m['sector'],
            ':archivo'     => $m['archivo'],
            ':nombre_orig' => $m['nombre_orig'],
            ':bytes'       => $m['bytes'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function eliminar(int $id): void
    {
        $this->db->prepare('DELETE FROM modelos WHERE id = ?')->execute([$id]);
    }

    public function guardarThumbnail(int $id, ?string $thumb): void
    {
        $this->db->prepare('UPDATE modelos SET thumbnail = ? WHERE id = ?')->execute([$thumb, $id]);
    }
}
