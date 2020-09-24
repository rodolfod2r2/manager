<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:59
 */

namespace application\assets\fpdf\pdfparser\filter;


class AsciiHex implements FilterInterface {

    public function decode($data) {
        $data = \preg_replace('/[^0-9A-Fa-f]/', '', \rtrim($data, '>'));
        if ((\strlen($data) % 2) === 1) {
            $data .= '0';
        }

        return \pack('H*', $data);
    }

    public function encode($data, $leaveEOD = false) {
        $t = \unpack('H*', $data);
        return \current($t)
            . ($leaveEOD ? '' : '>');
    }

}