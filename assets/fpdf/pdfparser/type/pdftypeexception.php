<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:15
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\PdfParserException;

class PdfTypeException extends PdfParserException {

    const NO_NEWLINE_AFTER_STREAM_KEYWORD = 0x0601;

}