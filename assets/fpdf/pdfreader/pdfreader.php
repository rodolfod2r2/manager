<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:17
 */

namespace application\assets\fpdf\pdfreader;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\type\PdfArray;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfIndirectObjectReference;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfType;

class PdfReader {

    protected $parser;
    protected $pageCount;
    protected $pages = [];

    public function __construct(PdfParser $parser) {
        $this->parser = $parser;
    }

    public function __destruct() {
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->parser->cleanUp();
    }

    public function getParser() {
        return $this->parser;
    }

    public function getPdfVersion() {
        return \implode('.', $this->parser->getPdfVersion());
    }

    public function getPage($pageNumber) {
        if (!\is_numeric($pageNumber)) {
            throw new \InvalidArgumentException(
                'Page number needs to be a number.'
            );
        }

        if ($pageNumber < 1 || $pageNumber > $this->getPageCount()) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Page number "%s" out of available page range (1 - %s)',
                    $pageNumber,
                    $this->getPageCount()
                )
            );
        }

        $this->readPages();

        $page = $this->pages[$pageNumber - 1];

        if ($page instanceof PdfIndirectObjectReference) {
            $readPages = function ($kids) use (&$readPages) {
                $kids = PdfArray::ensure($kids);

                /** @noinspection LoopWhichDoesNotLoopInspection */
                foreach ($kids->value as $reference) {
                    $reference = PdfIndirectObjectReference::ensure($reference);
                    $object = $this->parser->getIndirectObject($reference->value);
                    $type = PdfDictionary::get($object->value, 'Type');

                    if ($type->value === 'Pages') {
                        return $readPages(PdfDictionary::get($object->value, 'Kids'));
                    }

                    return $object;
                }

                throw new PdfReaderException(
                    'Kids array cannot be empty.',
                    PdfReaderException::KIDS_EMPTY
                );
            };

            $page = $this->parser->getIndirectObject($page->value);
            $dict = PdfType::resolve($page, $this->parser);
            $type = PdfDictionary::get($dict, 'Type');
            if ($type->value === 'Pages') {
                $kids = PdfType::resolve(PdfDictionary::get($dict, 'Kids'), $this->parser);
                $page = $this->pages[$pageNumber - 1] = $readPages($kids);
            } else {
                $this->pages[$pageNumber - 1] = $page;
            }
        }

        return new Page($page, $this->parser);
    }

    public function getPageCount() {
        if ($this->pageCount === null) {
            $catalog = $this->parser->getCatalog();

            $pages = PdfType::resolve(PdfDictionary::get($catalog, 'Pages'), $this->parser);
            $count = PdfType::resolve(PdfDictionary::get($pages, 'Count'), $this->parser);

            $this->pageCount = PdfNumeric::ensure($count)->value;
        }

        return $this->pageCount;
    }

    protected function readPages() {
        if (\count($this->pages) > 0) {
            return;
        }

        $readPages = function ($kids, $count) use (&$readPages) {
            $kids = PdfArray::ensure($kids);
            $isLeaf = $count->value === \count($kids->value);

            foreach ($kids->value as $reference) {
                $reference = PdfIndirectObjectReference::ensure($reference);

                if ($isLeaf) {
                    $this->pages[] = $reference;
                    continue;
                }

                $object = $this->parser->getIndirectObject($reference->value);
                $type = PdfDictionary::get($object->value, 'Type');

                if ($type->value === 'Pages') {
                    $readPages(PdfDictionary::get($object->value, 'Kids'), PdfDictionary::get($object->value, 'Count'));
                } else {
                    $this->pages[] = $object;
                }
            }
        };

        $catalog = $this->parser->getCatalog();
        $pages = PdfType::resolve(PdfDictionary::get($catalog, 'Pages'), $this->parser);
        $count = PdfType::resolve(PdfDictionary::get($pages, 'Count'), $this->parser);
        $kids = PdfType::resolve(PdfDictionary::get($pages, 'Kids'), $this->parser);
        $readPages($kids, $count);
    }

}