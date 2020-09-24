<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 15:56
 */

namespace application\assets\fpdf\pdfparser\crossreference;


interface ReaderInterface {

    public function getOffsetFor($objectNumber);

    public function getTrailer();

}