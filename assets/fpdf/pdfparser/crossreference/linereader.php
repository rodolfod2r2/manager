<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:56
 */

namespace application\assets\fpdf\pdfparser\crossreference;


use application\assets\fpdf\pdfparser\PdfParser;

class LineReader extends AbstractReader implements ReaderInterface {

    protected $offsets;

    public function __construct(PdfParser $parser) {
        $this->read($this->extract($parser->getStreamReader()));
        parent::__construct($parser);
    }

    protected function read($xrefContent) {
        // get eol markers in the first 100 bytes
        \preg_match_all("/(\r\n|\n|\r)/", \substr($xrefContent, 0, 100), $m);

        if (\count($m[0]) === 0) {
            throw new CrossReferenceException(
                'No data found in cross-reference.',
                CrossReferenceException::INVALID_DATA
            );
        }

        // count(array_count_values()) is faster then count(array_unique())
        // @see https://github.com/symfony/symfony/pull/23731
        // can be reverted for php7.2
        $differentLineEndings = \count(\array_count_values($m[0]));
        if ($differentLineEndings > 1) {
            $lines = \preg_split("/(\r\n|\n|\r)/", $xrefContent, -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $lines = \explode($m[0][0], $xrefContent);
        }

        unset($differentLineEndings, $m);
        $linesCount = \count($lines);
        $start = null;
        $entryCount = 0;

        $offsets = [];

        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0; $i < $linesCount; $i++) {
            $line = \trim($lines[$i]);
            if ($line) {
                $pieces = \explode(' ', $line);

                $c = \count($pieces);
                switch ($c) {
                    case 2:
                        $start = (int)$pieces[0];
                        $entryCount += (int)$pieces[1];
                        break;

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 3:
                        switch ($pieces[2]) {
                            case 'n':
                                $offsets[$start] = [(int)$pieces[0], (int)$pieces[1]];
                                $start++;
                                break 2;
                            case 'f':
                                $start++;
                                break 2;
                        }
                    // fall through if pieces doesn't match

                    default:
                        throw new CrossReferenceException(
                            \sprintf('Unexpected data in xref table (%s)', \implode(' ', $pieces)),
                            CrossReferenceException::INVALID_DATA
                        );
                }
            }
        }

        $this->offsets = $offsets;
    }

    protected function extract(StreamReader $reader) {
        $cycles = -1;
        $bytesPerCycle = 100;

        $reader->reset(null, $bytesPerCycle);

        while (
            ($trailerPos = \strpos($reader->getBuffer(false), 'trailer', \max($bytesPerCycle * $cycles++, 0))) === false
        ) {
            if (false === $reader->increaseLength($bytesPerCycle)) {
                break;
            }
        }

        if (false === $trailerPos) {
            throw new CrossReferenceException(
                'Unexpected end of cross reference. trailer-keyword not found.',
                CrossReferenceException::NO_TRAILER_FOUND
            );
        }

        $xrefContent = \substr($reader->getBuffer(false), 0, $trailerPos);
        $reader->reset($reader->getPosition() + $trailerPos);

        return $xrefContent;
    }

    public function getOffsetFor($objectNumber) {
        if (isset($this->offsets[$objectNumber])) {
            return $this->offsets[$objectNumber][0];
        }

        return false;
    }

    public function getOffsets() {
        return $this->offsets;
    }
}