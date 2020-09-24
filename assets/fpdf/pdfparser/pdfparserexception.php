<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:43
 */

namespace application\assets\fpdf\pdfparser;


use application\assets\fpdf\FpdiException;

class PdfParserException extends FpdiException {

    const NOT_IMPLEMENTED = 0x0001;
    const IMPLEMENTED_IN_FPDI_PDF_PARSER = 0x0002;
    const INVALID_DATA_TYPE = 0x0003;
    const FILE_HEADER_NOT_FOUND = 0x0004;
    const PDF_VERSION_NOT_FOUND = 0x0005;
    const INVALID_DATA_SIZE = 0x0006;

}