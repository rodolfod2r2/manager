<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:09
 */

namespace application\assets\fpdf\pdfparser\type;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\StreamReader;
use application\assets\fpdf\pdfparser\Tokenizer;

class PdfIndirectObject extends PdfType {

    public $objectNumber;
    public $generationNumber;

    public static function parse(
        $objectNumberToken,
        $objectGenerationNumberToken,
        PdfParser $parser,
        Tokenizer $tokenizer,
        StreamReader $reader
    ) {
        $value = $parser->readValue();
        if ($value === false) {
            return false;
        }

        $nextToken = $tokenizer->getNextToken();
        if ($nextToken === 'stream') {
            $value = PdfStream::parse($value, $reader);
        } elseif ($nextToken !== false) {
            $tokenizer->pushStack($nextToken);
        }

        $v = new self;
        $v->objectNumber = (int)$objectNumberToken;
        $v->generationNumber = (int)$objectGenerationNumberToken;
        $v->value = $value;

        return $v;
    }

    public static function create($objectNumber, $generationNumber, PdfType $value) {
        $v = new self;
        $v->objectNumber = (int)$objectNumber;
        $v->generationNumber = (int)$generationNumber;
        $v->value = $value;

        return $v;
    }

    public static function ensure($indirectObject) {
        return PdfType::ensureType(self::class, $indirectObject, 'Indirect object expected.');
    }

}