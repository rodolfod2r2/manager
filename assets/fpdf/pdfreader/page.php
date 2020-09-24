<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:16
 */

namespace application\assets\fpdf\pdfreader;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\type\PdfArray;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfNull;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfStream;
use application\assets\fpdf\pdfparser\type\PdfType;
use application\assets\fpdf\pdfreader\datastructure\Rectangle;

class Page {

    protected $pageObject;
    protected $pageDictionary;
    protected $parser;
    protected $inheritedAttributes;

    public function __construct(PdfIndirectObject $page, PdfParser $parser) {
        $this->pageObject = $page;
        $this->parser = $parser;
    }

    public function getWidthAndHeight($box = PageBoundaries::CROP_BOX, $fallback = true) {
        $boundary = $this->getBoundary($box, $fallback);
        if (false === $boundary) {
            return false;
        }

        $rotation = $this->getRotation();
        $interchange = ($rotation / 90) % 2;

        return [
            $interchange ? $boundary->getHeight() : $boundary->getWidth(),
            $interchange ? $boundary->getWidth() : $boundary->getHeight()
        ];
    }

    public function getBoundary($box = PageBoundaries::CROP_BOX, $fallback = true) {
        $value = $this->getAttribute($box);

        if (null !== $value) {
            return Rectangle::byPdfArray($value, $this->parser);
        }

        if (false === $fallback) {
            return false;
        }

        switch ($box) {
            case PageBoundaries::BLEED_BOX:
            case PageBoundaries::TRIM_BOX:
            case PageBoundaries::ART_BOX:
                return $this->getBoundary(PageBoundaries::CROP_BOX, true);
            case PageBoundaries::CROP_BOX:
                return $this->getBoundary(PageBoundaries::MEDIA_BOX, true);
        }

        return false;
    }

    public function getAttribute($name, $inherited = true) {
        $dict = $this->getPageDictionary();

        if (isset($dict->value[$name])) {
            return $dict->value[$name];
        }

        $inheritedKeys = ['Resources', 'MediaBox', 'CropBox', 'Rotate'];
        if ($inherited && \in_array($name, $inheritedKeys, true)) {
            if (null === $this->inheritedAttributes) {
                $this->inheritedAttributes = [];
                $inheritedKeys = \array_filter($inheritedKeys, function ($key) use ($dict) {
                    return !isset($dict->value[$key]);
                });

                if (\count($inheritedKeys) > 0) {
                    $parentDict = PdfType::resolve(PdfDictionary::get($dict, 'Parent'), $this->parser);
                    while ($parentDict instanceof PdfDictionary) {
                        foreach ($inheritedKeys as $index => $key) {
                            if (isset($parentDict->value[$key])) {
                                $this->inheritedAttributes[$key] = $parentDict->value[$key];
                                unset($inheritedKeys[$index]);
                            }
                        }

                        /** @noinspection NotOptimalIfConditionsInspection */
                        if (isset($parentDict->value['Parent']) && \count($inheritedKeys) > 0) {
                            $parentDict = PdfType::resolve(PdfDictionary::get($parentDict, 'Parent'), $this->parser);
                        } else {
                            break;
                        }
                    }
                }
            }

            if (isset($this->inheritedAttributes[$name])) {
                return $this->inheritedAttributes[$name];
            }
        }

        return null;
    }

    public function getPageDictionary() {
        if (null === $this->pageDictionary) {
            $this->pageDictionary = PdfDictionary::ensure(PdfType::resolve($this->getPageObject(), $this->parser));
        }

        return $this->pageDictionary;
    }

    public function getPageObject() {
        return $this->pageObject;
    }

    public function getRotation() {
        $rotation = $this->getAttribute('Rotate');
        if (null === $rotation) {
            return 0;
        }

        $rotation = PdfNumeric::ensure(PdfType::resolve($rotation, $this->parser))->value % 360;

        if ($rotation < 0) {
            $rotation += 360;
        }

        return $rotation;
    }

    public function getContentStream() {
        $dict = $this->getPageDictionary();
        $contents = PdfType::resolve(PdfDictionary::get($dict, 'Contents'), $this->parser);
        if ($contents instanceof PdfNull) {
            return '';
        }

        if ($contents instanceof PdfArray) {
            $result = [];
            foreach ($contents->value as $content) {
                $content = PdfType::resolve($content, $this->parser);
                if (!($content instanceof PdfStream)) {
                    continue;
                }
                $result[] = $content->getUnfilteredStream();
            }

            return \implode("\n", $result);
        }

        if (PdfStream instanceof $contents) {
            return $contents->getUnfilteredStream();
        }

        throw new PdfReaderException(
            'Array or stream expected.',
            PdfReaderException::UNEXPECTED_DATA_TYPE
        );
    }

}