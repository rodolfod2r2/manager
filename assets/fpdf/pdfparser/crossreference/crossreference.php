<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:54
 */

namespace application\assets\fpdf\pdfparser\crossreference;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfStream;
use application\assets\fpdf\pdfparser\type\PdfToken;

class CrossReference {

    static public $trailerSearchLength = 5500;
    protected $fileHeaderOffset = 0;
    protected $parser;
    protected $readers = [];

    public function __construct(PdfParser $parser, $fileHeaderOffset = 0) {
        $this->parser = $parser;
        $this->fileHeaderOffset = $fileHeaderOffset;
        $offset = $this->findStartXref();
        $reader = null;
        while ($offset !== false) {
            $reader = $this->readXref($offset + $this->fileHeaderOffset);
            $trailer = $reader->getTrailer();
            $this->checkForEncryption($trailer);
            $this->readers[] = $reader;
            if (isset($trailer->value['Prev'])) {
                $offset = $trailer->value['Prev']->value;
            } else {
                $offset = false;
            }
        }
        if ($reader instanceof FixedReader) {
            $reader->fixFaultySubSectionShift();
        }
    }

    protected function findStartXref() {
        $reader = $this->parser->getStreamReader();
        $reader->reset(-self::$trailerSearchLength, self::$trailerSearchLength);

        $buffer = $reader->getBuffer(false);
        $pos = \strrpos($buffer, 'startxref');
        $addOffset = 9;
        if (false === $pos) {
            // Some corrupted documents uses startref, instead of startxref
            $pos = \strrpos($buffer, 'startref');
            if (false === $pos) {
                throw new CrossReferenceException(
                    'Unable to find pointer to xref table',
                    CrossReferenceException::NO_STARTXREF_FOUND
                );
            }
            $addOffset = 8;
        }

        $reader->setOffset($pos + $addOffset);

        $value = $this->parser->readValue();
        if (!($value instanceof PdfNumeric)) {
            throw new CrossReferenceException(
                'Invalid data after startxref keyword.',
                CrossReferenceException::INVALID_DATA
            );
        }

        return $value->value;
    }

    protected function readXref($offset) {
        $this->parser->getStreamReader()->reset($offset);
        $this->parser->getTokenizer()->clearStack();
        $initValue = $this->parser->readValue();

        return $this->initReaderInstance($initValue);
    }

    protected function initReaderInstance($initValue) {
        $position = $this->parser->getStreamReader()->getPosition()
            + $this->parser->getStreamReader()->getOffset() + $this->fileHeaderOffset;

        if ($initValue instanceof PdfToken && $initValue->value === 'xref') {
            try {
                return new FixedReader($this->parser);
            } catch (CrossReferenceException $e) {
                $this->parser->getStreamReader()->reset($position);
                $this->parser->getTokenizer()->clearStack();

                return new LineReader($this->parser);
            }
        }

        if ($initValue instanceof PdfIndirectObject) {
            // check for encryption
            $stream = PdfStream::ensure($initValue->value);

            $type = PdfDictionary::get($stream->value, 'Type');
            if ($type->value !== 'XRef') {
                throw new CrossReferenceException(
                    'The xref position points to an incorrect object type.',
                    CrossReferenceException::INVALID_DATA
                );
            }

            $this->checkForEncryption($stream->value);

            throw new CrossReferenceException(
                'This PDF document probably uses a compression technique which is not supported by the ' .
                'free parser shipped with FPDI. (See https://www.setasign.com/fpdi-pdf-parser for more details)',
                CrossReferenceException::COMPRESSED_XREF
            );
        }

        throw new CrossReferenceException(
            'The xref position points to an incorrect object type.',
            CrossReferenceException::INVALID_DATA
        );
    }

    protected function checkForEncryption(PdfDictionary $dictionary) {
        if (isset($dictionary->value['Encrypt'])) {
            throw new CrossReferenceException(
                'This PDF document is encrypted and cannot be processed with FPDI.',
                CrossReferenceException::ENCRYPTED
            );
        }
    }

    public function getSize() {
        return $this->getTrailer()->value['Size']->value;
    }

    public function getTrailer() {
        return $this->readers[0]->getTrailer();
    }

    public function getIndirectObject($objectNumber) {

        $offset = $this->getOffsetFor($objectNumber);
        if (false === $offset) {
            throw new CrossReferenceException(
                \sprintf('Object (id:%s) not found.', $objectNumber),
                CrossReferenceException::OBJECT_NOT_FOUND
            );
        }

        $parser = $this->parser;

        $parser->getTokenizer()->clearStack();
        $parser->getStreamReader()->reset($offset + $this->fileHeaderOffset);

        $object = $parser->readValue();
        if (false === $object || !($object instanceof PdfIndirectObject)) {
            throw new CrossReferenceException(
                \sprintf('Object (id:%s) not found at location (%s).', $objectNumber, $offset),
                CrossReferenceException::OBJECT_NOT_FOUND
            );
        }

        if ($object->objectNumber !== $objectNumber) {
            throw new CrossReferenceException(
                \sprintf('Wrong object found, got %s while %s was expected.', $object->objectNumber, $objectNumber),
                CrossReferenceException::OBJECT_NOT_FOUND
            );
        }

        return $object;

    }

    public function getOffsetFor($objectNumber) {
        foreach ($this->getReaders() as $reader) {
            $offset = $reader->getOffsetFor($objectNumber);
            if (false !== $offset) {
                return $offset;
            }
        }

        return false;
    }

    public function getReaders() {
        return $this->readers;
    }

}