<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 22/03/2018
 * Time: 13:50
 */


use application\controller\ControllerHistorico;

require_once('autoload.php');

$history = new ControllerHistorico();
$history->desativarHistoricoproductownerFuncionario();

?>