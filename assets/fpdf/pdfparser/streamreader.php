<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:49
 */

namespace application\assets\fpdf\pdfparser;


class StreamReader {

    protected $closeStream;
    protected $stream;
    protected $position;
    protected $offset;
    protected $bufferLength;
    protected $totalLength;
    protected $buffer;

    public function __construct($stream, $closeStream = false) {
        if (!\is_resource($stream)) {
            throw new \InvalidArgumentException(
                'No stream given.'
            );
        }

        $metaData = \stream_get_meta_data($stream);
        if (!$metaData['seekable']) {
            throw new \InvalidArgumentException(
                'Given stream is not seekable!'
            );
        }

        $this->stream = $stream;
        $this->closeStream = $closeStream;
        $this->reset();
    }

    public function reset($pos = 0, $length = 200) {
        if (null === $pos) {
            $pos = $this->position + $this->offset;
        } elseif ($pos < 0) {
            $pos = \max(0, $this->getTotalLength() + $pos);
        }

        \fseek($this->stream, $pos);

        $this->position = $pos;
        $this->buffer = $length > 0 ? \fread($this->stream, $length) : '';
        $this->bufferLength = \strlen($this->buffer);
        $this->offset = 0;

        // If a stream wrapper is in use it is possible that
        // length values > 8096 will be ignored, so use the
        // increaseLength()-method to correct that behavior
        if ($this->bufferLength < $length && $this->increaseLength($length - $this->bufferLength)) {
            // increaseLength parameter is $minLength, so cut to have only the required bytes in the buffer
            $this->buffer = \substr($this->buffer, 0, $length);
            $this->bufferLength = \strlen($this->buffer);
        }
    }

    public function getTotalLength() {
        if (null === $this->totalLength) {
            $stat = \fstat($this->stream);
            $this->totalLength = $stat['size'];
        }

        return $this->totalLength;
    }

    public function increaseLength($minLength = 100) {
        $length = \max($minLength, 100);

        if (\feof($this->stream) || $this->getTotalLength() === $this->position + $this->bufferLength) {
            return false;
        }

        $newLength = $this->bufferLength + $length;
        do {
            $this->buffer .= \fread($this->stream, $newLength - $this->bufferLength);
            $this->bufferLength = \strlen($this->buffer);
        } while (($this->bufferLength !== $newLength) && !\feof($this->stream));

        return true;
    }

    public static function createByString($content, $maxMemory = 2097152) {
        $h = \fopen('php://temp/maxmemory:' . ((int)$maxMemory), 'r+b');
        \fwrite($h, $content);
        \rewind($h);

        return new self($h, true);
    }

    public static function createByFile($filename) {
        $h = \fopen($filename, 'rb');
        return new self($h, true);
    }

    public function __destruct() {
        if ($this->closeStream) {
            \fclose($this->stream);
        }
    }

    public function getBufferLength($atOffset = false) {
        if ($atOffset === false) {
            return $this->bufferLength;
        }

        return $this->bufferLength - $this->offset;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getBuffer($atOffset = true) {
        if (false === $atOffset) {
            return $this->buffer;
        }

        $string = \substr($this->buffer, $this->offset);

        return (string)$string;
    }

    public function readBytes($length, $position = null) {
        $length = (int)$length;
        if (null !== $position) {
            // check if needed bytes are available in the current buffer
            if (!($position >= $this->position && $position < $this->position + $this->bufferLength)) {
                $this->reset($position, $length);
                $offset = $this->offset;
            } else {
                $offset = $position - $this->position;
            }
        } else {
            $offset = $this->offset;
        }

        if (($offset + $length) > $this->bufferLength &&
            ((!$this->increaseLength($length)) || ($offset + $length) > $this->bufferLength)
        ) {
            return false;
        }

        $bytes = \substr($this->buffer, $offset, $length);
        $this->offset = $offset + $length;

        return $bytes;
    }

    public function readLine($length = 1024) {
        if (false === $this->ensureContent()) {
            return false;
        }

        $line = '';
        while ($this->ensureContent()) {
            $char = $this->readByte();

            if ($char === "\n") {
                break;
            }

            if ($char === "\r") {
                if ($this->getByte() === "\n") {
                    $this->addOffset(1);
                }
                break;
            }

            $line .= $char;

            if (\strlen($line) >= $length) {
                break;
            }
        }

        return $line;
    }

    public function ensureContent() {
        while ($this->offset >= $this->bufferLength) {
            if (!$this->increaseLength()) {
                return false;
            }
        }
        return true;
    }

    public function readByte($position = null) {
        if (null !== $position) {
            $position = (int)$position;
            // check if needed bytes are available in the current buffer
            if (!($position >= $this->position && $position < $this->position + $this->bufferLength)) {
                $this->reset($position);
                $offset = $this->offset;
            } else {
                $offset = $position - $this->position;
            }
        } else {
            $offset = $this->offset;
        }

        if ($offset >= $this->bufferLength &&
            ((!$this->increaseLength()) || $offset >= $this->bufferLength)
        ) {
            return false;
        }

        $this->offset = $offset + 1;
        return $this->buffer[$offset];
    }

    public function getByte($position = null) {
        $position = (int)(null !== $position ? $position : $this->offset);
        if ($position >= $this->bufferLength &&
            (!$this->increaseLength() || $position >= $this->bufferLength)
        ) {
            return false;
        }

        return $this->buffer[$position];
    }

    public function addOffset($offset) {
        $this->setOffset($this->offset + $offset);
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setOffset($offset) {
        if ($offset > $this->bufferLength || $offset < 0) {
            throw new \OutOfRangeException(
                \sprintf('Offset (%s) out of range (length: %s)', $offset, $this->bufferLength)
            );
        }

        $this->offset = (int)$offset;
    }

    public function getStream() {
        return $this->stream;
    }

    public function ensure($pos, $length) {
        if ($pos >= $this->position
            && $pos < ($this->position + $this->bufferLength)
            && ($this->position + $this->bufferLength) >= ($pos + $length)
        ) {
            $this->offset = $pos - $this->position;
        } else {
            $this->reset($pos, $length);
        }
    }

}