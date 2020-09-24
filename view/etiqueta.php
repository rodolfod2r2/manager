<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 26/01/2018
 * Time: 12:16
 */


use application\assets\fpdf\Fpdf;

require_once('autoload.php');


$pdf = new FPDF('P', 'mm', array(216, 279));
$pdf->SetMargins(0, 0);
$pdf->SetDisplayMode(100, 'single');
$pdf->AddPage();


$posicao = @$_GET['posicao'];
$nome = @$_GET['nome'];
$tipo = @$_GET['tipo'];
$setor = @$_GET['setor'];

switch ($posicao) {
    case '1':
        $yPosition = 12;
        $xPosition = 8;
        break;
    case '2':
        $yPosition = 54;
        $xPosition = 8;
        break;
    case '3':
        $yPosition = 108;
        $xPosition = 8;
        break;
    case '4':
        $yPosition = 162;
        $xPosition = 8;
        break;
    case '5':
        $yPosition = 216;
        $xPosition = 8;
        break;
    case '6':
        $yPosition = 12;
        $xPosition = 108;
        break;
    case '7':
        $yPosition = 54;
        $xPosition = 108;
        break;
    case '8':
        $yPosition = 108;
        $xPosition = 108;
        break;
    case '9':
        $yPosition = 162;
        $xPosition = 108;
        break;
    case '10':
        $yPosition = 216;
        $xPosition = 108;
        break;
    default:
        $yPosition = 8;
        $xPosition = 108;
        break;
}

$LarguraCelula = 96;

$pdf->SetFont('Helvetica', '', 16);
$pdf->SetXY($xPosition, $yPosition);
$pdf->Cell($LarguraCelula, 5, utf8_decode($nome), 0, 0, 'C');
$pdf->Ln(8);
$pdf->Cell($LarguraCelula, 5, '', 0, 0, 'C');
$pdf->Ln(8);
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetX($xPosition);
$pdf->Cell($LarguraCelula, 5,  utf8_decode($tipo), 0, 0, 'C');
$pdf->Ln(8);
$pdf->SetFont('Helvetica', '', 12);
$pdf->SetX($xPosition);
$pdf->Cell($LarguraCelula, 5, utf8_decode($setor), 0, 0, 'C');
$pdf->Ln(4);
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetX($xPosition);
$pdf->Cell($LarguraCelula, 5, 'Setor', 0, 0, 'C');
$pdf->Ln();

$pdf->Output();