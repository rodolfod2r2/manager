<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:39
 */

namespace application\assets\fpdf\fpdfi;


use application\assets\fpdf\FpdiTrait;
use application\assets\fpdf\pdfparser\crossreference\CrossReferenceException;
use application\assets\fpdf\pdfparser\filter\AsciiHex;
use application\assets\fpdf\pdfparser\type\PdfHexString;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfNull;
use application\assets\fpdf\pdfparser\type\PdfNumeric;
use application\assets\fpdf\pdfparser\type\PdfStream;
use application\assets\fpdf\pdfparser\type\PdfString;


class TcpdfFpdi extends TCPDF {

    use FpdiTrait {
        writePdfType as fpdiWritePdfType;
        useImportedPage as fpdiUseImportedPage;
    }

    const VERSION = '2.0.0';
    protected $templateId = 0;
    protected $currentObjectNumber;

    public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {
        return $this->useImportedPage($tpl, $x, $y, $width, $height, $adjustPageSize);
    }

    public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = false) {
        $size = $this->fpdiUseImportedPage($pageId, $x, $y, $width, $height, $adjustPageSize);
        if ($this->inxobj) {
            $importedPage = $this->importedPages[$pageId];
            $this->xobjects[$this->xobjid]['importedPages'][$importedPage['id']] = $pageId;
        }

        return $size;
    }

    public function getTemplateSize($tpl, $width = null, $height = null) {
        return $this->getImportedPageSize($tpl, $width, $height);
    }

    protected function getNextTemplateId() {
        return $this->templateId++;
    }

    protected function _getxobjectdict() {
        $out = parent::_getxobjectdict();

        foreach ($this->importedPages as $key => $pageData) {
            $out .= '/' . $pageData['id'] . ' ' . $pageData['objectNumber'] . ' 0 R ';
        }

        return $out;
    }

    protected function _putxobjects() {
        foreach ($this->importedPages as $key => $pageData) {
            $this->currentObjectNumber = $this->_newobj();
            $this->importedPages[$key]['objectNumber'] = $this->currentObjectNumber;
            $this->currentReaderId = $pageData['readerId'];
            $this->writePdfType($pageData['stream']);
            $this->_put('endobj');
        }

        foreach (\array_keys($this->readers) as $readerId) {
            $parser = $this->getPdfReader($readerId)->getParser();
            $this->currentReaderId = $readerId;

            while (($objectNumber = \array_pop($this->objectsToCopy[$readerId])) !== null) {
                try {
                    $object = $parser->getIndirectObject($objectNumber);

                } catch (CrossReferenceException $e) {
                    if ($e->getCode() === CrossReferenceException::OBJECT_NOT_FOUND) {
                        $object = PdfIndirectObject::create($objectNumber, 0, new PdfNull());
                    } else {
                        throw $e;
                    }
                }

                $this->writePdfType($object);
            }
        }

        // let's prepare resources for imported pages in templates
        foreach ($this->xobjects as $xObjectId => $data) {
            if (!isset($data['importedPages'])) {
                continue;
            }

            foreach ($data['importedPages'] as $id => $pageKey) {
                $page = $this->importedPages[$pageKey];
                $this->xobjects[$xObjectId]['xobjects'][$id] = ['n' => $page['objectNumber']];
            }
        }


        parent::_putxobjects();
        $this->currentObjectNumber = null;
    }

    protected function _newobj($objid = '') {
        $this->_out($this->_getobj($objid));
        return $this->n;
    }

    protected function writePdfType(PdfType $value) {
        if (!$this->encrypted) {
            $this->fpdiWritePdfType($value);
            return;
        }

        if ($value instanceof PdfString) {
            $string = PdfString::unescape($value->value);
            $string = $this->_encrypt_data($this->currentObjectNumber, $string);
            $value->value = \TCPDF_STATIC::_escape($string);

        } elseif ($value instanceof PdfHexString) {
            $filter = new AsciiHex();
            $string = $filter->decode($value->value);
            $string = $this->_encrypt_data($this->currentObjectNumber, $string);
            $value->value = $filter->encode($string, true);

        } elseif ($value instanceof PdfStream) {
            $stream = $value->getStream();
            $stream = $this->_encrypt_data($this->currentObjectNumber, $stream);
            $dictionary = $value->value;
            $dictionary->value['Length'] = PdfNumeric::create(\strlen($stream));
            $value = PdfStream::create($dictionary, $stream);

        } elseif ($value instanceof PdfIndirectObject) {
            /**
             * @var $value PdfIndirectObject
             */
            $this->currentObjectNumber = $this->objectMap[$this->currentReaderId][$value->objectNumber];
        }

        $this->fpdiWritePdfType($value);
    }

    protected function _put($s, $newLine = true) {
        if ($newLine) {
            $this->setBuffer($s . "\n");
        } else {
            $this->setBuffer($s);
        }
    }

}