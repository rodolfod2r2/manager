<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:37
 */

namespace application\assets\fpdf;


use application\assets\fpdf\pdfparser\PdfParser;
use application\assets\fpdf\pdfparser\pdfparser as fpdipdfparser;
use application\assets\fpdf\pdfparser\StreamReader;
use application\assets\fpdf\pdfparser\type\PdfArray;
use application\assets\fpdf\pdfparser\type\PdfBoolean;
use application\assets\fpdf\pdfparser\type\PdfDictionary;
use application\assets\fpdf\pdfparser\type\PdfHexString;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfIndirectObjectReference;
use application\assets\fpdf\pdfparser\type\PdfName;
use application\assets\fpdf\pdfparser\type\PdfNull;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfStream;
use application\assets\fpdf\pdfparser\type\PdfString;
use application\assets\fpdf\pdfparser\type\PdfToken;
use application\assets\fpdf\pdfparser\type\PdfType;
use application\assets\fpdf\pdfreader\PageBoundaries;
use application\assets\fpdf\pdfreader\PdfReader;
use application\assets\fpdf\pdfreader\PdfReaderException;


trait FpdiTrait {

    protected $readers = [];
    protected $currentReaderId;
    protected $importedPages = [];
    protected $objectMap = [];
    protected $objectsToCopy = [];

    public function setSourceFile($file) {
        $this->currentReaderId = $this->getPdfReaderId($file);
        $this->objectsToCopy[$this->currentReaderId] = [];

        $reader = $this->getPdfReader($this->currentReaderId);
        $this->setMinPdfVersion($reader->getPdfVersion());

        return $reader->getPageCount();
    }

    protected function getPdfReaderId($file) {
        if (\is_resource($file)) {
            $id = (string)$file;
        } elseif (\is_string($file)) {
            $id = \realpath($file);
            if (false === $id) {
                $id = $file;
            }
        } elseif (\is_object($file)) {
            $id = \spl_object_hash($file);
        } else {
            throw new \InvalidArgumentException(
                \sprintf('Invalid type in $file parameter (%s)', \gettype($file))
            );
        }

        if (isset($this->readers[$id])) {
            return $id;
        }

        if (\is_resource($file)) {
            $streamReader = new StreamReader($file);
        } elseif (\is_string($file)) {
            $streamReader = StreamReader::createByFile($file);
        } else {
            $streamReader = $file;
        }

        $reader = new PdfReader($this->getPdfParserInstance($streamReader));
        $this->readers[$id] = $reader;

        return $id;
    }

    protected function getPdfParserInstance(StreamReader $streamReader) {
        /** @noinspection PhpUndefinedClassInspection */
        if (\class_exists(FpdiPdfParser::class)) {
            /** @noinspection PhpUndefinedClassInspection */
            return new FpdiPdfParser($streamReader);
        }

        return new PdfParser($streamReader);
    }

    protected function getPdfReader($id) {
        if (isset($this->readers[$id])) {
            return $this->readers[$id];
        }

        throw new \InvalidArgumentException(
            \sprintf('No pdf reader with the given id (%s) exists.', $id)
        );
    }

    protected function setMinPdfVersion($pdfVersion) {
        if (\version_compare($pdfVersion, $this->PDFVersion, '>')) {
            $this->PDFVersion = $pdfVersion;
        }
    }

    public function importPage($pageNumber, $box = PageBoundaries::CROP_BOX, $groupXObject = true) {
        if (null === $this->currentReaderId) {
            throw new \BadMethodCallException('No reader initiated. Call setSourceFile() first.');
        }

        $pageId = $this->currentReaderId;

        $pageNumber = (int)$pageNumber;
        $pageId .= '|' . $pageNumber . '|' . ($groupXObject ? '1' : '0');

        // for backwards compatibility with FPDI 1
        $box = \ltrim($box, '/');
        if (!PageBoundaries::isValidName($box)) {
            throw new \InvalidArgumentException(
                \sprintf('Box name is invalid: "%s"', $box)
            );
        }

        $pageId .= '|' . $box;

        if (isset($this->importedPages[$pageId])) {
            return $pageId;
        }

        $reader = $this->getPdfReader($this->currentReaderId);
        $page = $reader->getPage($pageNumber);

        $bbox = $page->getBoundary($box);
        if ($bbox === false) {
            throw new PdfReaderException(
                \sprintf("Page doesn't have a boundary box (%s).", $box),
                PdfReaderException::MISSING_DATA
            );
        }

        $dict = new PdfDictionary();
        $dict->value['Type'] = PdfName::create('XObject');
        $dict->value['Subtype'] = PdfName::create('Form');
        $dict->value['FormType'] = PdfNumeric::create(1);
        $dict->value['BBox'] = $bbox->toPdfArray();

        if ($groupXObject) {
            $this->setMinPdfVersion('1.4');
            $dict->value['Group'] = PdfDictionary::create([
                'Type' => PdfName::create('Group'),
                'S' => PdfName::create('Transparency')
            ]);
        }

        $resources = $page->getAttribute('Resources');
        if ($resources !== null) {
            $dict->value['Resources'] = $resources;
        }

        list($width, $height) = $page->getWidthAndHeight($box);

        $a = 1;
        $b = 0;
        $c = 0;
        $d = 1;
        $e = -$bbox->getLlx();
        $f = -$bbox->getLly();

        $rotation = $page->getRotation();

        if ($rotation !== 0) {
            $rotation *= -1;
            $angle = $rotation * M_PI / 180;
            $a = \cos($angle);
            $b = \sin($angle);
            $c = -$b;
            $d = $a;

            switch ($rotation) {
                case -90:
                    $e = -$bbox->getLly();
                    $f = $bbox->getUrx();
                    break;
                case -180:
                    $e = $bbox->getUrx();
                    $f = $bbox->getUry();
                    break;
                case -270:
                    $e = $bbox->getUry();
                    $f = -$bbox->getLlx();
                    break;
            }
        }

        // we need to rotate/translate
        if ($a != 1 || $b != 0 || $c != 0 || $d != 1 || $e != 0 || $f != 0) {
            $dict->value['Matrix'] = PdfArray::create([
                PdfNumeric::create($a), PdfNumeric::create($b), PdfNumeric::create($c),
                PdfNumeric::create($d), PdfNumeric::create($e), PdfNumeric::create($f)
            ]);
        }

        // try to use the existing content stream
        $pageDict = $page->getPageDictionary();

        $contentsObject = PdfType::resolve(PdfDictionary::get($pageDict, 'Contents'), $reader->getParser(), true);
        $contents = PdfType::resolve($contentsObject, $reader->getParser());

        // just copy the stream reference if it is only a single stream
        if (($contentsIsStream = ($contents instanceof PdfStream))
            || ($contents instanceof PdfArray && \count($contents->value) === 1)
        ) {
            if ($contentsIsStream) {
                /**
                 * @var PdfIndirectObject $contentsObject
                 */
                $stream = $contents;
            } else {
                $stream = PdfType::resolve($contents->value[0], $reader->getParser());
            }

            $filter = PdfDictionary::get($stream->value, 'Filter');
            if (!$filter instanceof PdfNull) {
                $dict->value['Filter'] = $filter;
            }
            $length = PdfType::resolve(PdfDictionary::get($stream->value, 'Length'), $reader->getParser());
            $dict->value['Length'] = $length;
            $stream->value = $dict;

            // otherwise extract it from the array and re-compress the whole stream
        } else {
            $streamContent = $this->compress
                ? \gzcompress($page->getContentStream())
                : $page->getContentStream();

            $dict->value['Length'] = PdfNumeric::create(\strlen($streamContent));
            if ($this->compress) {
                $dict->value['Filter'] = PdfName::create('FlateDecode');
            }

            $stream = PdfStream::create($dict, $streamContent);
        }

        $this->importedPages[$pageId] = [
            'objectNumber' => null,
            'readerId' => $this->currentReaderId,
            'id' => 'TPL' . $this->getNextTemplateId(),
            'width' => $width / $this->k,
            'height' => $height / $this->k,
            'stream' => $stream
        ];

        return $pageId;
    }

    public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {
        if (\is_array($x)) {
            unset($x['pageId']);
            \extract($x, EXTR_IF_EXISTS);
            /** @noinspection NotOptimalIfConditionsInspection */
            if (\is_array($x)) {
                $x = 0;
            }
        }

        if (!isset($this->importedPages[$pageId])) {
            throw new \InvalidArgumentException('Imported page does not exist!');
        }

        $importedPage = $this->importedPages[$pageId];

        $originalSize = $this->getTemplateSize($pageId);
        $newSize = $this->getTemplateSize($pageId, $width, $height);
        if ($adjustPageSize) {
            $this->setPageFormat($newSize, $newSize['orientation']);
        }

        $this->_out(
        // reset standard values, translate and scale
            \sprintf(
                'q 0 J 1 w 0 j 0 G 0 g %.4F 0 0 %.4F %.4F %.4F cm /%s Do Q',
                ($newSize['width'] / $originalSize['width']),
                ($newSize['height'] / $originalSize['height']),
                $x * $this->k,
                ($this->h - $y - $newSize['height']) * $this->k,
                $importedPage['id']
            )
        );

        return $newSize;
    }

    public function getImportedPageSize($tpl, $width = null, $height = null) {
        if (isset($this->importedPages[$tpl])) {
            $importedPage = $this->importedPages[$tpl];

            if ($width === null && $height === null) {
                $width = $importedPage['width'];
                $height = $importedPage['height'];
            } elseif ($width === null) {
                $width = $height * $importedPage['width'] / $importedPage['height'];
            }

            if ($height === null) {
                $height = $width * $importedPage['height'] / $importedPage['width'];
            }

            if ($height <= 0. || $width <= 0.) {
                throw new \InvalidArgumentException('Width or height parameter needs to be larger than zero.');
            }

            return [
                'width' => $width,
                'height' => $height,
                0 => $width,
                1 => $height,
                'orientation' => $width > $height ? 'L' : 'P'
            ];
        }

        return false;
    }

    protected function writePdfType(PdfType $value) {
        if ($value instanceof PdfNumeric) {
            if (\is_int($value->value)) {
                $this->_put($value->value . ' ', false);
            } else {
                $this->_put(\rtrim(\rtrim(\sprintf('%.5F', $value->value), '0'), '.') . ' ', false);
            }

        } elseif ($value instanceof PdfName) {
            $this->_put('/' . $value->value . ' ', false);

        } elseif ($value instanceof PdfString) {
            $this->_put('(' . $value->value . ')', false);

        } elseif ($value instanceof PdfHexString) {
            $this->_put('<' . $value->value . '>');

        } elseif ($value instanceof PdfBoolean) {
            $this->_put($value->value ? 'true ' : 'false ', false);

        } elseif ($value instanceof PdfArray) {
            $this->_put('[', false);
            foreach ($value->value as $entry) {
                $this->writePdfType($entry);
            }
            $this->_put(']');

        } elseif ($value instanceof PdfDictionary) {
            $this->_put('<<', false);
            foreach ($value->value as $name => $entry) {
                $this->_put('/' . $name . ' ', false);
                $this->writePdfType($entry);
            }
            $this->_put('>>');

        } elseif ($value instanceof PdfToken) {
            $this->_put($value->value);

        } elseif ($value instanceof PdfNull) {
            $this->_put('null ');

        } elseif ($value instanceof PdfStream) {
            /**
             * @var $value PdfStream
             */
            $this->writePdfType($value->value);
            $this->_put('stream');
            $this->_put($value->getStream());
            $this->_put('endstream');

        } elseif ($value instanceof PdfIndirectObjectReference) {
            if (!isset($this->objectMap[$this->currentReaderId])) {
                $this->objectMap[$this->currentReaderId] = [];
            }

            if (!isset($this->objectMap[$this->currentReaderId][$value->value])) {
                $this->objectMap[$this->currentReaderId][$value->value] = ++$this->n;
                $this->objectsToCopy[$this->currentReaderId][] = $value->value;
            }

            $this->_put($this->objectMap[$this->currentReaderId][$value->value] . ' 0 R ', false);

        } elseif ($value instanceof PdfIndirectObject) {
            /**
             * @var $value PdfIndirectObject
             */
            $n = $this->objectMap[$this->currentReaderId][$value->objectNumber];
            $this->_newobj($n);
            $this->writePdfType($value->value);
            $this->_put('endobj');
        }
    }

}

