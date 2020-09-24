<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:03
 */

namespace application\assets\fpdf\pdfparser\filter;


use application\assets\fpdf\pdfparser\PdfParserException;

class FilterException extends PdfParserException {

    const UNSUPPORTED_FILTER = 0x0201;
    const NOT_IMPLEMENTED = 0x0202;

}