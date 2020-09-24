<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:55
 */

namespace application\assets\fpdf\pdfparser\crossreference;

use application\assets\fpdf\pdfparser\PdfParserException;

class CrossReferenceException extends PdfParserException {

    const INVALID_DATA = 0x0101;
    const XREF_MISSING = 0x0102;
    const ENTRIES_TOO_LARGE = 0x0103;
    const ENTRIES_TOO_SHORT = 0x0104;
    const NO_ENTRIES = 0x0105;
    const NO_TRAILER_FOUND = 0x0106;
    const NO_STARTXREF_FOUND = 0x0107;
    const NO_XREF_FOUND = 0x0108;
    const UNEXPECTED_END = 0x0109;
    const OBJECT_NOT_FOUND = 0x010A;
    const COMPRESSED_XREF = 0x010B;
    const ENCRYPTED = 0x010C;

}