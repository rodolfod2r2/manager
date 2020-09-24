<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 07/03/2018
 * Time: 11:17
 */

use application\controller\Controllerproductowner;
use application\model\vo\productowner;

$productowner = new productowner();
$controllerproductowner = new Controllerproductowner();

?>

<div class="row">
    <div class="col-md-pull-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">productowner</h4>
            </div>
            <div class="panel-body">
                <?php $controllerproductowner->loadForm($productowner); ?>
            </div>
        </div>
    </div>
</div>
