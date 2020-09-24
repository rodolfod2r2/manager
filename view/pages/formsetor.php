<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 07/03/2018
 * Time: 11:17
 */

use application\controller\ControllerSetor;
use application\model\vo\Setor;

$setor = new Setor();
$controllerSetor = new ControllerSetor();


?>

<div class="row">
    <div class="col-md-pull-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Setor</h4>
            </div>
            <div class="panel-body">
                <?php $controllerSetor->loadForm($setor); ?>
            </div>
        </div>
    </div>
</div>
