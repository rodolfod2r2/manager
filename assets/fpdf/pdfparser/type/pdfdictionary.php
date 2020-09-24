<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:08
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\StreamReader;
use application\assets\fpdf\pdfparser\Tokenizer;

class PdfDictionary extends PdfType {

    public static function parse(Tokenizer $tokenizer, StreamReader $streamReader, PdfParser $parser) {

        $entries = [];

        while (true) {
            $token = $tokenizer->getNextToken();
            if ($token === '>' && $streamReader->getByte() === '>') {
                $streamReader->addOffset(1);
                break;
            }

            $key = $parser->readValue($token);
            if (false === $key) {
                return false;
            }

            if (!($key instanceof PdfName)) {
                $lastToken = null;

                while (($token = $tokenizer->getNextToken()) !== '>' && $token !== false && $lastToken !== '>') {
                    $lastToken = $token;
                }

                if ($token === false) {
                    return false;
                }

                break;
            }

            $value = $parser->readValue();
            if (false === $value) {
                return false;
            }

            if ($value instanceof PdfNull) {
                continue;
            }

            if ($value instanceof PdfToken && $value->value === '>' && $streamReader->getByte() === '>') {
                $streamReader->addOffset(1);
                break;
            }
            $entries[$key->value] = $value;
        }

        $v = new self;
        $v->value = $entries;

        return $v;
    }

    public static function create(array $entries = []) {
        $v = new self;
        $v->value = $entries;

        return $v;
    }

    public static function get($dictionary, $key, PdfType $default = null) {
        $dictionary = self::ensure($dictionary);

        if (isset($dictionary->value[$key])) {
            return $dictionary->value[$key];
        }

        return $default === null
            ? new PdfNull()
            : $default;
    }

    public static function ensure($dictionary) {
        return PdfType::ensureType(self::class, $dictionary, 'Dictionary value expected.');
    }

}