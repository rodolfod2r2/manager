<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:07
 */

namespace application\assets\fpdf\pdfparser\type;


class PdfBoolean extends PdfType {

    public static function create($value) {
        $v = new self;
        $v->value = (boolean)$value;
        return $v;
    }

    public static function ensure($value) {
        return PdfType::ensureType(self::class, $value, 'Boolean value expected.');
    }

}