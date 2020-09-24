<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:09
 */

namespace application\assets\fpdf\pdfparser\type;


class PdfIndirectObjectReference extends PdfType {

    public $generationNumber;

    public static function create($objectNumber, $generationNumber) {
        $v = new self;
        $v->value = (int)$objectNumber;
        $v->generationNumber = (int)$generationNumber;

        return $v;
    }

    public static function ensure($value) {
        return PdfType::ensureType(self::class, $value, 'Indirect reference value expected.');
    }

}