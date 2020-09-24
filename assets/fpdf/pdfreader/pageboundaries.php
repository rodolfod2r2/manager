<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:17
 */

namespace application\assets\fpdf\pdfreader;


class PageBoundaries {

    const MEDIA_BOX = 'MediaBox';
    const CROP_BOX = 'CropBox';
    const BLEED_BOX = 'BleedBox';
    const TRIM_BOX = 'TrimBox';
    const ART_BOX = 'ArtBox';

    public static $all = array(
        self::MEDIA_BOX,
        self::CROP_BOX,
        self::BLEED_BOX,
        self::TRIM_BOX,
        self::ART_BOX
    );

    public static function isValidName($name) {
        return \in_array($name, self::$all, true);
    }

}