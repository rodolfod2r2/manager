<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 07/03/2018
 * Time: 11:17
 */

use application\controller\ControllerCargo;
use application\model\vo\Cargo;

$cargo = new Cargo();
$controllerCargo = new ControllerCargo();


?>

<div class="row">
    <div class="col-md-pull-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Cargo</h4>
            </div>
            <div class="panel-body">
                <?php $controllerCargo->loadForm($cargo); ?>
            </div>
        </div>
    </div>
</div>
