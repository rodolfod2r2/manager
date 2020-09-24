<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:51
 */

namespace application\assets\fpdf\pdfparser;


class Tokenizer {

    protected $streamReader;
    protected $stack = [];

    public function __construct(StreamReader $streamReader) {
        $this->streamReader = $streamReader;
    }

    public function getStreamReader() {
        return $this->streamReader;
    }

    public function clearStack() {
        $this->stack = [];
    }

    public function pushStack($token) {
        $this->stack[] = $token;
    }

    public function getNextToken() {
        $token = \array_pop($this->stack);
        if (null !== $token) {
            return $token;
        }

        if (($byte = $this->streamReader->readByte()) === false) {
            return false;
        }

        if ($byte === "\x20" ||
            $byte === "\x0A" ||
            $byte === "\x0D" ||
            $byte === "\x0C" ||
            $byte === "\x09" ||
            $byte === "\x00"
        ) {
            if (false === $this->leapWhiteSpaces()) {
                return false;
            }
            $byte = $this->streamReader->readByte();
        }

        switch ($byte) {
            case '/':
            case '[':
            case ']':
            case '(':
            case ')':
            case '{':
            case '}':
            case '<':
            case '>':
                return $byte;
            case '%':
                $this->streamReader->readLine();
                return $this->getNextToken();
        }

        /* This way is faster than checking single bytes.
         */
        $bufferOffset = $this->streamReader->getOffset();
        do {
            $lastBuffer = $this->streamReader->getBuffer(false);
            $pos = \strcspn(
                $lastBuffer,
                "\x00\x09\x0A\x0C\x0D\x20()<>[]{}/%",
                $bufferOffset
            );
        } while (
            // Break the loop if a delimiter or white space char is matched
            // in the current buffer or increase the buffers length
            $lastBuffer !== false &&
            (
                $bufferOffset + $pos === \strlen($lastBuffer) &&
                $this->streamReader->increaseLength()
            )
        );

        $result = \substr($lastBuffer, $bufferOffset - 1, $pos + 1);
        $this->streamReader->setOffset($bufferOffset + $pos);

        return $result;
    }

    public function leapWhiteSpaces() {
        do {
            if (!$this->streamReader->ensureContent()) {
                return false;
            }

            $buffer = $this->streamReader->getBuffer(false);
            $matches = \strspn($buffer, "\x20\x0A\x0C\x0D\x09\x00", $this->streamReader->getOffset());
            if ($matches > 0) {
                $this->streamReader->addOffset($matches);
            }
        } while ($this->streamReader->getOffset() >= $this->streamReader->getBufferLength());

        return true;
    }

}