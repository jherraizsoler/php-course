<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Capa de acceso a datos para las tareas (el "modelo").
 * TODA la SQL vive aquí, con consultas preparadas. El resto de la app no toca la BBDD.
 */
final class TareaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::conexion();
    }

    /** @return array<int,array<string,mixed>> */
    public function todas(): array
    {
        return $this->db
            ->query('SELECT * FROM tareas ORDER BY completada ASC, id DESC')
            ->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tareas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function crear(string $titulo): int
    {
        $stmt = $this->db->prepare('INSERT INTO tareas (titulo) VALUES (?)');
        $stmt->execute([$titulo]);
        return (int) $this->db->lastInsertId();
    }

    public function actualizarTitulo(int $id, string $titulo): void
    {
        $stmt = $this->db->prepare('UPDATE tareas SET titulo = ? WHERE id = ?');
        $stmt->execute([$titulo, $id]);
    }

    public function alternarCompletada(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE tareas SET completada = NOT completada WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function eliminar(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM tareas WHERE id = ?');
        $stmt->execute([$id]);
    }
}
