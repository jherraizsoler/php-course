<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Dashboard de métricas del CRUD.
 *
 *   /dashboard       → panel con tarjetas + gráficas Chart.js (datos servidos por PHP)
 *   /dashboard/png   → MISMA gráfica generada en el SERVIDOR con GD (imagen PNG)
 *
 * Demuestra los dos enfoques: render en cliente (Chart.js) y render en servidor (GD).
 */
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    /** Calcula las métricas a partir de la tabla usuarios. */
    private function metricas()
    {
        $usuarios = $this->Usuario_model->obtener_todos();

        $porDominio = [];
        $porMes     = [];
        foreach ($usuarios as $u) {
            $cola = strrchr($u->email, '@');
            $dom  = $cola ? strtolower(substr($cola, 1)) : '(sin dominio)';
            $porDominio[$dom] = ($porDominio[$dom] ?? 0) + 1;

            $mes = (! empty($u->creado_en)) ? substr($u->creado_en, 0, 7) : 'sin fecha';
            $porMes[$mes] = ($porMes[$mes] ?? 0) + 1;
        }
        arsort($porDominio);
        ksort($porMes);

        return [
            'usuarios'   => $usuarios,
            'total'      => count($usuarios),
            'porDominio' => $porDominio,
            'porMes'     => $porMes,
        ];
    }

    public function index()
    {
        $this->load->view('dashboard/index', $this->metricas());
    }

    /** Gráfica de barras "usuarios por dominio" dibujada en el servidor con GD. */
    public function png()
    {
        $datos = $this->metricas()['porDominio'];

        $w = 640; $h = 340; $pad = 46; $base = $h - 54;
        $img = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);

        $bg   = imagecolorallocate($img, 13, 19, 48);
        $barA = imagecolorallocate($img, 99, 102, 241);
        $barB = imagecolorallocate($img, 34, 211, 238);
        $txt  = imagecolorallocate($img, 220, 225, 255);
        $soft = imagecolorallocate($img, 149, 160, 200);
        $grid = imagecolorallocate($img, 40, 50, 88);

        imagefilledrectangle($img, 0, 0, $w, $h, $bg);
        imagestring($img, 5, $pad, 14, 'Usuarios por dominio (generado con GD)', $txt);

        // Líneas de rejilla horizontales
        for ($g = 0; $g <= 4; $g++) {
            $y = 50 + ($base - 50) * $g / 4;
            imageline($img, $pad, (int) $y, $w - 20, (int) $y, $grid);
        }

        $max = $datos ? max($datos) : 1;
        $n   = max(count($datos), 1);
        $bw  = ($w - $pad - 24) / $n;
        $i   = 0;
        foreach ($datos as $dom => $count) {
            $bh = (int) (($base - 60) * ($count / $max));
            $x1 = (int) ($pad + $i * $bw + 10);
            $x2 = (int) ($pad + ($i + 1) * $bw - 10);
            $y1 = $base - $bh;
            $col = ($i % 2 === 0) ? $barA : $barB;
            imagefilledrectangle($img, $x1, $y1, $x2, $base, $col);
            imagestring($img, 3, $x1, $y1 - 16, (string) $count, $txt);
            imagestring($img, 2, $x1, $base + 6, substr($dom, 0, 14), $soft);
            $i++;
        }

        header('Content-Type: image/png');
        header('Cache-Control: no-store');
        imagepng($img);
        imagedestroy($img);
        exit;
    }
}
