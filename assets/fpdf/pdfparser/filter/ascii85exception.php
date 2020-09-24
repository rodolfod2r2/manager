<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:58
 */

namespace application\assets\fpdf\pdfparser\filter;


class Ascii85Exception extends FilterException {

    const ILLEGAL_CHAR_FOUND = 0x0301;
    const ILLEGAL_LENGTH = 0x0302;

}