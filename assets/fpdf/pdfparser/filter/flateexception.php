<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:05
 */

namespace application\assets\fpdf\pdfparser\filter;


class FlateException extends FilterException {

    const NO_ZLIB = 0x0401;
    const DECOMPRESS_ERROR = 0x0402;

}