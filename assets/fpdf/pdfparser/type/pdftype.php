<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:14
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\PdfParser;

class PdfType {

    public $value;

    public static function resolve(PdfType $value, PdfParser $parser, $stopAtIndirectObject = false) {
        if ($value instanceof PdfIndirectObject) {
            if ($stopAtIndirectObject === true) {
                return $value;
            }

            return self::resolve($value->value, $parser, $stopAtIndirectObject);
        }

        if ($value instanceof PdfIndirectObjectReference) {
            return self::resolve($parser->getIndirectObject($value->value), $parser, $stopAtIndirectObject);
        }

        return $value;
    }

    protected static function ensureType($type, $value, $errorMessage) {
        if (!($value instanceof $type)) {
            throw new PdfTypeException(
                $errorMessage,
                PdfTypeException::INVALID_DATA_TYPE
            );
        }

        return $value;
    }

}