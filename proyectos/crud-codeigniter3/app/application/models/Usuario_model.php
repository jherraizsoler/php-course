<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modelo de usuarios. Encapsula TODO el acceso a la tabla `usuarios`.
 * Usa el Query Builder de CI3, que escapa los valores (anti-inyección SQL).
 *
 * Copia en: application/models/Usuario_model.php
 */
class Usuario_model extends CI_Model
{
    private $tabla = 'usuarios';

    /** @return object[] */
    public function obtener_todos()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->tabla)
            ->result();   // array de objetos
    }

    /** @return object|null */
    public function obtener($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->tabla)
            ->row();      // un solo objeto (o null)
    }

    /** @return int ID del nuevo registro */
    public function crear(array $datos)
    {
        $this->db->insert($this->tabla, $datos);
        return $this->db->insert_id();
    }

    public function actualizar($id, array $datos)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->tabla, $datos);
    }

    public function eliminar($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->tabla);
    }

    /** Ejemplo de búsqueda con LIKE */
    public function buscar($texto)
    {
        return $this->db
            ->like('nombre', $texto)
            ->or_like('email', $texto)
            ->get($this->tabla)
            ->result();
    }
}
