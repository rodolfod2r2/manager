<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 07/03/2018
 * Time: 11:17
 */


use application\controller\ControllerInventario;
use application\model\vo\Inventario;

$inventario = new Inventario();
$controllerInventario = new ControllerInventario();

?>

<div class="row">
    <div class="col-md-pull-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Inventario</h4>
            </div>
            <div class="panel-body">
                <?php $controllerInventario->loadForm($inventario); ?>
            </div>
        </div>
    </div>
</div>
