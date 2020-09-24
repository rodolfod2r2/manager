<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 28/12/2017
 * Time: 16:04
 */

namespace application\assets\fpdf\pdfparser\filter;


interface FilterInterface {

    public function decode($data);

}