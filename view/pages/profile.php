<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 07/03/2018
 * Time: 11:17
 */

use application\controller\ControllerFuncionario;
use application\model\vo\Funcionario;

$funcionario = new Funcionario();
$controllerFuncionario = new ControllerFuncionario();
$funcionario->setPessoaPK($id);

?>

<div class="row">
    <div class="col-md-pull-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Profile <?php echo $nome ?></h4>
            </div>
            <div class="panel-body">
                <?php $controllerFuncionario->exibirDetalhar($funcionario); ?>
            </div>
        </div>
    </div>
</div>
