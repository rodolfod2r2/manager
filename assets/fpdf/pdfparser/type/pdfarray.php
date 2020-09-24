<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:07
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\Tokenizer;

class PdfArray extends PdfType {

    public static function parse(Tokenizer $tokenizer, PdfParser $parser) {
        $result = [];

        while (($token = $tokenizer->getNextToken()) !== ']') {
            if ($token === false || ($value = $parser->readValue($token)) === false) {
                return false;
            }

            $result[] = $value;
        }

        $v = new self;
        $v->value = $result;

        return $v;
    }

    public static function create(array $values = []) {
        $v = new self;
        $v->value = $values;

        return $v;
    }

    public static function ensure($array, $size = null) {
        $result = PdfType::ensureType(self::class, $array, 'Array value expected.');

        if ($size !== null && \count($array->value) !== $size) {
            throw new PdfTypeException(
                \sprintf('Array with %s entries expected.', $size),
                PdfTypeException::INVALID_DATA_SIZE
            );
        }

        return $result;
    }

}