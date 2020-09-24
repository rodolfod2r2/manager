<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 18:24
 */


use application\assets\fpdf\fpdi;

require_once('autoload.php');
require_once('fpdf.php');

$pdf = new Fpdi();
$url = $_SERVER['REQUEST_URI'];

$pages_count = $pdf->setSourceFile('../../resources/edicao.pdf');

for ($i = 1; $i <= $pages_count; $i++) {
    $pdf->AddPage();

    $tplIdx = $pdf->importPage($i);

    $pdf->useTemplate($tplIdx);

    $pdf->SetFont('Helvetica');
    $pdf->SetFontSize(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetXY(50, 2);
    $pdf->Write(0, "Usuario: rodolfod2r2@gmail.com" . $url);
}

$pdf->Output();