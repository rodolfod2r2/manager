<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:12
 */

namespace application\assets\fpdf\pdfparser\type;


class PdfNumeric extends PdfType {

    public static function create($value) {
        $v = new self;
        $v->value = $value + 0;

        return $v;
    }

    public static function ensure($value) {
        return PdfType::ensureType(self::class, $value, 'Numeric value expected.');
    }

}