<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:17
 */

namespace application\assets\fpdf\pdfreader;


use application\assets\fpdf\FpdiException;

class PdfReaderException extends FpdiException {

    const KIDS_EMPTY = 0x0101;
    const UNEXPECTED_DATA_TYPE = 0x0102;
    const MISSING_DATA = 0x0103;

}