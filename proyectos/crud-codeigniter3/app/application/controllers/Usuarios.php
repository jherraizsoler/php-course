<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controlador de ejemplo: CRUD de usuarios en CodeIgniter 3.
 *
 * CÓMO USARLO:
 *   Copia este archivo en  application/controllers/Usuarios.php  de un proyecto CI3.
 *   Copia también el modelo y las vistas correspondientes.
 *
 * Rutas que genera CI automáticamente:
 *   /usuarios            → index()   (listado)
 *   /usuarios/crear      → crear()   (formulario + guardar)
 *   /usuarios/editar/5   → editar(5)
 *   /usuarios/eliminar/5 → eliminar(5)
 */
class Usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
    }

    /** Listado de usuarios */
    public function index()
    {
        $data['usuarios'] = $this->Usuario_model->obtener_todos();
        $this->load->view('usuarios/index', $data);
    }

    /** Crear: muestra el formulario y procesa el POST */
    public function crear()
    {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[usuarios.email]');

        if ($this->form_validation->run() === false) {
            // Primera carga o validación fallida → mostrar formulario
            $this->load->view('usuarios/form', ['accion' => 'Crear']);
            return;
        }

        $this->Usuario_model->crear([
            'nombre' => $this->input->post('nombre', true),
            'email'  => $this->input->post('email', true),
        ]);
        $this->session->set_flashdata('ok', 'Usuario creado correctamente.');
        redirect('usuarios');
    }

    /** Editar un usuario existente */
    public function editar($id)
    {
        $usuario = $this->Usuario_model->obtener($id);
        if (!$usuario) {
            show_404();
        }

        $this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === false) {
            $this->load->view('usuarios/form', ['accion' => 'Editar', 'usuario' => $usuario]);
            return;
        }

        $this->Usuario_model->actualizar($id, [
            'nombre' => $this->input->post('nombre', true),
            'email'  => $this->input->post('email', true),
        ]);
        $this->session->set_flashdata('ok', 'Usuario actualizado.');
        redirect('usuarios');
    }

    /** Eliminar */
    public function eliminar($id)
    {
        $this->Usuario_model->eliminar($id);
        $this->session->set_flashdata('ok', 'Usuario eliminado.');
        redirect('usuarios');
    }
}
