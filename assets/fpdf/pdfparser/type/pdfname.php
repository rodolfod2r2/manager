<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:10
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\StreamReader;
use application\assets\fpdf\pdfparser\Tokenizer;

class PdfName extends PdfType {

    public static function parse(Tokenizer $tokenizer, StreamReader $streamReader) {
        $v = new self;
        if (\strspn($streamReader->getByte(), "\x00\x09\x0A\x0C\x0D\x20()<>[]{}/%") === 0) {
            $v->value = (string)$tokenizer->getNextToken();
            return $v;
        }

        $v->value = '';
        return $v;
    }

    public static function create($string) {
        $v = new self;
        $v->value = $string;

        return $v;
    }

    public static function ensure($name) {
        return PdfType::ensureType(self::class, $name, 'Name value expected.');
    }

}