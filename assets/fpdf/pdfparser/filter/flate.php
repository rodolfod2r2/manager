<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:04
 */

namespace application\assets\fpdf\pdfparser\filter;


class Flate implements FilterInterface {

    public function decode($data) {
        if ($this->extensionLoaded()) {
            $oData = $data;
            $data = @((\strlen($data) > 0) ? \gzuncompress($data) : '');
            if (false === $data) {
                // Try this fallback
                $tries = 1;
                while ($tries < 10 && ($data === false || \strlen($data) < (\strlen($oData) - $tries - 1))) {
                    $data = @(\gzinflate(\substr($oData, $tries)));
                    $tries++;
                }

                if (false === $data) {
                    throw new FlateException(
                        'Error while decompressing stream.',
                        FlateException::DECOMPRESS_ERROR
                    );
                }
            }
        } else {
            throw new FlateException(
                'To handle FlateDecode filter, enable zlib support in PHP.',
                FlateException::NO_ZLIB
            );
        }

        return $data;
    }

    protected function extensionLoaded() {
        return \extension_loaded('zlib');
    }

}