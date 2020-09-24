<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:18
 */

namespace application\assets\fpdf\pdfreader\datastructure;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\type\PdfArray;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfType;

class Rectangle {

    protected $llx;
    protected $lly;
    protected $urx;
    protected $ury;

    public function __construct($ax, $ay, $bx, $by) {
        $this->llx = \min($ax, $bx);
        $this->lly = \min($ay, $by);
        $this->urx = \max($ax, $bx);
        $this->ury = \max($ay, $by);
    }

    public static function byPdfArray($array, PdfParser $parser) {
        $array = PdfArray::ensure(PdfType::resolve($array, $parser), 4)->value;
        $ax = PdfNumeric::ensure(PdfType::resolve($array[0], $parser))->value;
        $ay = PdfNumeric::ensure(PdfType::resolve($array[1], $parser))->value;
        $bx = PdfNumeric::ensure(PdfType::resolve($array[2], $parser))->value;
        $by = PdfNumeric::ensure(PdfType::resolve($array[3], $parser))->value;

        return new self($ax, $ay, $bx, $by);
    }

    public function getWidth() {
        return $this->urx - $this->llx;
    }

    public function getHeight() {
        return $this->ury - $this->lly;
    }

    public function getLlx() {
        return $this->llx;
    }

    public function getLly() {
        return $this->lly;
    }

    public function getUrx() {
        return $this->urx;
    }

    public function getUry() {
        return $this->ury;
    }

    public function toArray() {
        return [
            $this->llx,
            $this->lly,
            $this->urx,
            $this->ury
        ];
    }

    public function toPdfArray() {
        $array = new PdfArray();
        $array->value[] = PdfNumeric::create($this->llx);
        $array->value[] = PdfNumeric::create($this->lly);
        $array->value[] = PdfNumeric::create($this->urx);
        $array->value[] = PdfNumeric::create($this->ury);

        return $array;
    }

}