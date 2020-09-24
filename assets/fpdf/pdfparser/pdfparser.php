<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:40
 */

namespace application\assets\fpdf\pdfparser;


use application\assets\fpdf\pdfparser\crossreference\crossreference;
use application\assets\fpdf\pdfparser\type\PdfArray;
use application\assets\fpdf\pdfparser\type\PdfBoolean;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfHexString;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfIndirectObjectReference;
use application\assets\fpdf\pdfparser\type\PdfName;
use application\assets\fpdf\pdfparser\type\PdfNull;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfString;
use application\assets\fpdf\pdfparser\type\PdfToken;
use application\assets\fpdf\pdfparser\type\PdfType;

class PdfParser {

    protected $streamReader;
    protected $tokenizer;
    protected $fileHeader;
    protected $fileHeaderOffset;
    protected $xref;
    protected $objects = [];

    public function __construct(StreamReader $streamReader) {
        $this->streamReader = $streamReader;
        $this->tokenizer = new Tokenizer($streamReader);
    }


    public function cleanUp() {
        $this->xref = null;
    }

    public function getStreamReader() {
        return $this->streamReader;
    }


    public function getTokenizer() {
        return $this->tokenizer;
    }

    public function getPdfVersion() {
        $this->resolveFileHeader();

        if (\preg_match('/%PDF-(\d)\.(\d)/', $this->fileHeader, $result) === 0) {
            throw new PdfParserException(
                'Unable to extract PDF version from file header.',
                PdfParserException::PDF_VERSION_NOT_FOUND
            );
        }
        list(, $major, $minor) = $result;

        $catalog = $this->getCatalog();
        if (isset($catalog->value['Version'])) {
            list($major, $minor) = \explode('.', PdfType::resolve($catalog->value['Version'], $this)->value);
        }

        return [(int)$major, (int)$minor];
    }

    protected function resolveFileHeader() {
        if ($this->fileHeader) {
            return $this->fileHeaderOffset;
        }

        $this->streamReader->reset(0);
        $offset = false;
        $maxIterations = 1000;
        while (true) {
            $buffer = $this->streamReader->getBuffer(false);
            $offset = \strpos($buffer, '%PDF-');
            if (false === $offset) {
                if (!$this->streamReader->increaseLength(100) || (--$maxIterations === 0)) {
                    throw new PdfParserException(
                        'Unable to find PDF file header.',
                        PdfParserException::FILE_HEADER_NOT_FOUND
                    );
                }
                continue;
            }
            break;
        }

        $this->fileHeaderOffset = $offset;
        $this->streamReader->setOffset($offset);

        $this->fileHeader = \trim($this->streamReader->readLine());
        return $this->fileHeaderOffset;
    }

    public function getCatalog() {
        $xref = $this->getCrossReference();
        $trailer = $xref->getTrailer();

        $catalog = PdfType::resolve(PdfDictionary::get($trailer, 'Root'), $this);

        return PdfDictionary::ensure($catalog);
    }

    public function getCrossReference() {
        if (null === $this->xref) {
            $this->xref = new CrossReference($this, $this->resolveFileHeader());
        }

        return $this->xref;
    }

    public function getIndirectObject($objectNumber, $cache = false) {
        $objectNumber = (int)$objectNumber;
        if (isset($this->objects[$objectNumber])) {
            return $this->objects[$objectNumber];
        }

        $xref = $this->getCrossReference();
        $object = $xref->getIndirectObject($objectNumber);

        if ($cache) {
            $this->objects[$objectNumber] = $object;
        }

        return $object;
    }

    public function readValue($token = null) {
        if (null === $token) {
            $token = $this->tokenizer->getNextToken();
        }

        if (false === $token) {
            return false;
        }

        switch ($token) {
            case '(':
                return PdfString::parse($this->streamReader);

            case '<':
                if ($this->streamReader->getByte() === '<') {
                    $this->streamReader->addOffset(1);
                    return PdfDictionary::parse($this->tokenizer, $this->streamReader, $this);
                }

                return PdfHexString::parse($this->streamReader);

            case '/':
                return PdfName::parse($this->tokenizer, $this->streamReader);

            case '[':
                return PdfArray::parse($this->tokenizer, $this);

            default:
                if (\is_numeric($token)) {
                    if (($token2 = $this->tokenizer->getNextToken()) !== false) {
                        if (\is_numeric($token2)) {
                            if (($token3 = $this->tokenizer->getNextToken()) !== false) {
                                switch ($token3) {
                                    case 'obj':
                                        return PdfIndirectObject::parse(
                                            $token,
                                            $token2,
                                            $this,
                                            $this->tokenizer,
                                            $this->streamReader
                                        );
                                    case 'R':
                                        return PdfIndirectObjectReference::create($token, $token2);
                                }

                                $this->tokenizer->pushStack($token3);
                            }
                        }

                        $this->tokenizer->pushStack($token2);
                    }

                    return PdfNumeric::create($token);
                }

                if ('true' === $token || 'false' === $token) {
                    return PdfBoolean::create('true' === $token);
                }

                if ('null' === $token) {
                    return new PdfNull();
                }

                $v = new PdfToken();
                $v->value = $token;

                return $v;
        }
    }
}