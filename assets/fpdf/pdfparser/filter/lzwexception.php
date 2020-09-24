<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:06
 */

namespace application\assets\fpdf\pdfparser\filter;


class LzwException extends FilterException {

    const LZW_FLAVOUR_NOT_SUPPORTED = 0x0501;

}