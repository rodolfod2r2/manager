<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:33
 */

namespace application\assets\fpdf;


use application\assets\fpdf\pdfparser\crossreference\CrossReferenceException;
use application\assets\fpdf\pdfparser\type\PdfIndirectObject;
use application\assets\fpdf\pdfparser\type\PdfNull;

class Fpdi extends FpdfTpl {

    use FpdiTrait;
    const VERSION = '2.0.0';

    public function useTemplate($tpl, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = true) {
        if (isset($this->importedPages[$tpl])) {
            $size = $this->useImportedPage($tpl, $x, $y, $width, $height, $adjustPageSize);
            if ($this->currentTemplateId !== null) {
                $this->templates[$this->currentTemplateId]['resources']['templates']['importedPages'][$tpl] = $tpl;
            }
            return $size;
        }

        return parent::useTemplate($tpl, $x, $y, $width, $height, $adjustPageSize);
    }


    public function getTemplateSize($tpl, $width = null, $height = null) {
        $size = parent::getTemplateSize($tpl, $width, $height);
        if (false === $size) {
            return $this->getImportedPageSize($tpl, $width, $height);
        }

        return $size;
    }

    protected function _putimages() {
        $this->currentReaderId = null;
        parent::_putimages();

        foreach ($this->importedPages as $key => $pageData) {
            $this->_newobj();
            $this->importedPages[$key]['objectNumber'] = $this->n;
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

        $this->currentReaderId = null;
    }

    protected function _put($s, $newLine = true) {
        if ($newLine) {
            $this->buffer .= $s . "\n";
        } else {
            $this->buffer .= $s;
        }
    }

    protected function _putxobjectdict() {
        foreach ($this->importedPages as $key => $pageData) {
            $this->_put('/' . $pageData['id'] . ' ' . $pageData['objectNumber'] . ' 0 R');
        }

        parent::_putxobjectdict();
    }

}