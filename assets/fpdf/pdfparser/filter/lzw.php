<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:05
 */

namespace application\assets\fpdf\pdfparser\filter;


class Lzw implements FilterInterface {

    protected $data;
    protected $sTable = [];
    protected $dataLength = 0;
    protected $tIdx;
    protected $bitsToGet = 9;
    protected $bytePointer;
    protected $nextData = 0;
    protected $nextBits = 0;
    protected $andTable = [511, 1023, 2047, 4095];

    public function decode($data) {
        if ($data[0] == 0x00 && $data[1] == 0x01) {
            throw new LzwException(
                'LZW flavour not supported.',
                LzwException::LZW_FLAVOUR_NOT_SUPPORTED
            );
        }

        $this->initsTable();

        $this->data = $data;
        $this->dataLength = \strlen($data);

        // Initialize pointers
        $this->bytePointer = 0;

        $this->nextData = 0;
        $this->nextBits = 0;

        $oldCode = 0;

        $uncompData = '';

        while (($code = $this->getNextCode()) != 257) {
            if ($code == 256) {
                $this->initsTable();
                $code = $this->getNextCode();

                if ($code == 257) {
                    break;
                }

                $uncompData .= $this->sTable[$code];
                $oldCode = $code;

            } else {
                if ($code < $this->tIdx) {
                    $string = $this->sTable[$code];
                    $uncompData .= $string;

                    $this->addStringToTable($this->sTable[$oldCode], $string[0]);
                    $oldCode = $code;
                } else {
                    $string = $this->sTable[$oldCode];
                    $string .= $string[0];
                    $uncompData .= $string;

                    $this->addStringToTable($string);
                    $oldCode = $code;
                }
            }
        }

        return $uncompData;
    }

    protected function initsTable() {
        $this->sTable = [];

        for ($i = 0; $i < 256; $i++) {
            $this->sTable[$i] = \chr($i);
        }

        $this->tIdx = 258;
        $this->bitsToGet = 9;
    }

    protected function getNextCode() {
        if ($this->bytePointer == $this->dataLength) {
            return 257;
        }

        $this->nextData = ($this->nextData << 8) | (\ord($this->data[$this->bytePointer++]) & 0xff);
        $this->nextBits += 8;

        if ($this->nextBits < $this->bitsToGet) {
            $this->nextData = ($this->nextData << 8) | (\ord($this->data[$this->bytePointer++]) & 0xff);
            $this->nextBits += 8;
        }

        $code = ($this->nextData >> ($this->nextBits - $this->bitsToGet)) & $this->andTable[$this->bitsToGet - 9];
        $this->nextBits -= $this->bitsToGet;

        return $code;
    }

    protected function addStringToTable($oldString, $newString = '') {
        $string = $oldString . $newString;

        // Add this new String to the table
        $this->sTable[$this->tIdx++] = $string;

        if ($this->tIdx == 511) {
            $this->bitsToGet = 10;
        } elseif ($this->tIdx == 1023) {
            $this->bitsToGet = 11;
        } elseif ($this->tIdx == 2047) {
            $this->bitsToGet = 12;
        }
    }

}