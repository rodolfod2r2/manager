<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:53
 */

namespace application\assets\fpdf\pdfparser\crossreference;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfToken;

class AbstractReader {

    protected $parser;
    protected $trailer;

    public function __construct(PdfParser $parser) {
        $this->parser = $parser;
        $this->readTrailer();
    }

    protected function readTrailer() {
        $trailerKeyword = $this->parser->readValue();
        if ($trailerKeyword === false ||
            !($trailerKeyword instanceof PdfToken) ||
            $trailerKeyword->value !== 'trailer'
        ) {
            throw new CrossReferenceException(
                \sprintf(
                    'Unexpected end of cross reference. "trailer"-keyword expected, got: %s',
                    $trailerKeyword instanceof PdfToken ? $trailerKeyword->value : 'nothing'
                ),
                CrossReferenceException::UNEXPECTED_END
            );
        }

        $trailer = $this->parser->readValue();
        if ($trailer === false || !($trailer instanceof PdfDictionary)) {
            throw new CrossReferenceException(
                'Unexpected end of cross reference. Trailer not found.',
                CrossReferenceException::UNEXPECTED_END
            );
        }

        $this->trailer = $trailer;
    }

    public function getTrailer() {
        return $this->trailer;
    }

}