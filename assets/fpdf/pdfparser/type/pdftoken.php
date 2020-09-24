<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:13
 */

namespace application\assets\fpdf\pdfparser\type;


class PdfToken extends PdfType {

    public static function create($token) {
        $v = new self;
        $v->value = $token;

        return $v;
    }

    public static function ensure($token) {
        return PdfType::ensureType(self::class, $token, 'Token value expected.');
    }

}