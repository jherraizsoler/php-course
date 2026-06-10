<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * PDF corporativo: cabecera con banda de color + logo y pie con paginación y autoría.
 */
class Informe_PDF extends TCPDF
{
    public $marca     = 'Jorge Herraiz Soler';
    public $subtitulo = 'PHP Course · Informe de usuarios';
    public $logo      = '';

    public function Header()
    {
        $ancho = $this->getPageWidth();

        // Fina barra de acento suave en el borde superior (nada agresivo a la vista)
        $this->SetFillColor(129, 140, 248);          // indigo suave
        $this->Rect(0, 0, $ancho, 2.2, 'F');

        // Logo con ESQUINAS REDONDEADAS (recorte por clipping)
        if ($this->logo !== '' && @file_exists($this->logo)) {
            $x = 15; $y = 12; $s = 18;
            $this->StartTransform();
            $this->RoundedRect($x, $y, $s, $s, 4.5, '1111', 'CNZ');   // CNZ = clip
            $this->Image($this->logo, $x, $y, $s, $s, '', '', '', true, 300);
            $this->StopTransform();
        }

        // Marca + subtítulo: texto oscuro sobre blanco (lectura limpia, bien integrado)
        $this->SetTextColor(31, 41, 55);             // slate-800
        $this->SetFont('helvetica', 'B', 15);
        $this->setFontSpacing(0.2);
        $this->SetXY(38, 13);
        $this->Cell(0, 8, $this->marca, 0, 2, 'L');
        $this->setFontSpacing(0);
        $this->SetFont('helvetica', '', 9.5);
        $this->SetTextColor(107, 114, 128);          // gray-500
        $this->Cell(0, 6, $this->subtitulo, 0, 0, 'L');

        // Fecha discreta a la derecha
        $this->SetFont('helvetica', '', 8.5);
        $this->SetTextColor(156, 163, 184);
        $this->SetXY(-80, 16);
        $this->Cell(65, 6, 'Generado: ' . date('d/m/Y H:i'), 0, 0, 'R');

        // Línea divisoria suave bajo la cabecera
        $this->SetDrawColor(228, 231, 237);
        $this->SetLineWidth(0.25);
        $this->Line(15, 35, $ancho - 15, 35);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetDrawColor(228, 231, 237);
        $this->SetLineWidth(0.25);
        $this->Line(15, $this->GetY(), $this->getPageWidth() - 15, $this->GetY());

        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(156, 163, 184);
        $this->Cell(0, 11, $this->marca . ' · PHP Course', 0, 0, 'L');
        $this->Cell(0, 11, 'Página ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}

/**
 * Exportación del listado de usuarios a CSV, Excel (.xlsx) y PDF.
 *
 *   /export/csv   → CSV  (nativo, sin librerías)
 *   /export/xlsx  → Excel (PhpSpreadsheet)
 *   /export/pdf   → PDF  (TCPDF)
 *
 * Las librerías se cargan por el autoload de Composer (config.php → composer_autoload).
 */
class Export extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    /** Datos a exportar (mismos que muestra el CRUD). */
    private function usuarios()
    {
        return $this->Usuario_model->obtener_todos();
    }

    // ---------------------------------------------------------------- CSV
    public function csv()
    {
        $usuarios = $this->usuarios();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="usuarios.csv"');
        header('Cache-Control: max-age=0');

        $salida = fopen('php://output', 'w');
        fwrite($salida, "\xEF\xBB\xBF");                 // BOM UTF-8 → tildes correctas en Excel
        fputcsv($salida, ['ID', 'Nombre', 'Email']);     // cabecera
        foreach ($usuarios as $u) {
            fputcsv($salida, [(int) $u->id, $u->nombre, $u->email]);
        }
        fclose($salida);
        exit;   // evita que CI3 añada nada después del fichero
    }

    // --------------------------------------------------------------- Excel
    public function xlsx()
    {
        $usuarios = $this->usuarios();

        $libro = new Spreadsheet();
        $hoja  = $libro->getActiveSheet();
        $hoja->setTitle('Usuarios');

        // Cabecera
        $hoja->fromArray(['ID', 'Nombre', 'Email'], null, 'A1');
        $hoja->getStyle('A1:C1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $hoja->getStyle('A1:C1')->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');

        // Filas
        $fila = 2;
        foreach ($usuarios as $u) {
            $hoja->fromArray([(int) $u->id, $u->nombre, $u->email], null, 'A' . $fila);
            $fila++;
        }
        foreach (['A', 'B', 'C'] as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="usuarios.xlsx"');
        header('Cache-Control: max-age=0');

        (new Xlsx($libro))->save('php://output');
        exit;
    }

    // ----------------------------------------------------------------- PDF
    public function pdf()
    {
        $usuarios = $this->usuarios();

        $total = count($usuarios);

        $pdf = new Informe_PDF('P', 'mm', 'A4', true, 'UTF-8');
        $pdf->logo = FCPATH . '../../../logo_jorge.png';   // logo del autor en la cabecera
        $pdf->SetCreator('PHP Course');
        $pdf->SetAuthor('Jorge Herraiz Soler');
        $pdf->SetTitle('Informe de usuarios');
        $pdf->SetMargins(15, 40, 15);        // top 40mm → deja sitio a la cabecera
        $pdf->SetAutoPageBreak(true, 22);    // sitio para el pie
        $pdf->AddPage();

        // Resumen
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->setFontSpacing(0.2);
        $pdf->SetTextColor(31, 41, 55);              // slate-800
        $pdf->Cell(0, 8, 'Listado de usuarios', 0, 1, 'L');
        $pdf->setFontSpacing(0);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetTextColor(120, 128, 145);           // gris tranquilo
        $pdf->Cell(0, 6, 'Total de registros: ' . $total . '    ·    Base de datos: curso    ·    Tabla: usuarios', 0, 1, 'L');
        $pdf->Ln(5);

        // Tabla: cabecera gris claro (texto pizarra) + filas zebra muy suaves
        $filas = '';
        $i = 0;
        foreach ($usuarios as $u) {
            $bg = ($i % 2 === 0) ? '#FFFFFF' : '#F7F8FB';
            $filas .= '<tr>'
                . '<td align="center" bgcolor="' . $bg . '"><span style="color:#475569;">' . (int) $u->id . '</span></td>'
                . '<td bgcolor="' . $bg . '"><span style="color:#1F2937;">&nbsp;' . htmlspecialchars($u->nombre, ENT_QUOTES, 'UTF-8') . '</span></td>'
                . '<td bgcolor="' . $bg . '"><span style="color:#475569;">&nbsp;' . htmlspecialchars($u->email, ENT_QUOTES, 'UTF-8') . '</span></td>'
                . '</tr>';
            $i++;
        }

        $html = '<table border="0" cellpadding="8" cellspacing="0" style="font-size:10px;line-height:1.4;">'
            . '<thead><tr>'
            . '<td width="14%" align="center" bgcolor="#EEF1F6"><span style="color:#334155;font-weight:bold;letter-spacing:1px;">ID</span></td>'
            . '<td width="41%" bgcolor="#EEF1F6"><span style="color:#334155;font-weight:bold;letter-spacing:1px;">&nbsp;NOMBRE</span></td>'
            . '<td width="45%" bgcolor="#EEF1F6"><span style="color:#334155;font-weight:bold;letter-spacing:1px;">&nbsp;EMAIL</span></td>'
            . '</tr></thead><tbody>' . $filas . '</tbody></table>';

        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output('informe-usuarios.pdf', 'D');
        exit;
    }
}
