<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:08
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\StreamReader;

class PdfHexString extends PdfType {

    public static function parse(StreamReader $streamReader) {
        $bufferOffset = $streamReader->getOffset();

        while (true) {
            $buffer = $streamReader->getBuffer(false);
            $pos = \strpos($buffer, '>', $bufferOffset);
            if (false === $pos) {
                if (!$streamReader->increaseLength()) {
                    return false;
                }
                continue;
            }

            break;
        }

        $result = \substr($buffer, $bufferOffset, $pos - $bufferOffset);
        $streamReader->setOffset($pos + 1);

        $v = new self;
        $v->value = $result;

        return $v;
    }

    public static function create($string) {
        $v = new self;
        $v->value = $string;

        return $v;
    }

    public static function ensure($hexString) {
        return PdfType::ensureType(self::class, $hexString, 'Hex string value expected.');
    }

}