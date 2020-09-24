<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 03/03/2018
 * Time: 00:10
 */

use application\controller\ControllerSetor;
use application\model\vo\Setor;

$setor = new Setor();
$controllerSetor = new ControllerSetor();

?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 people-list">
        <div class="people-options clearfix">
            <div class="btn-toolbar">
                <div class="col-sm-12 col-md-10 col-lg-11">
                    <div class="input-group mb0">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id='pesquisar' placeholder="Pesquisar" class="form-control">
                    </div>
                </div>
                <div class="col-sm-12 col-md-2 col-lg-1">
                    <a href='../setor/novo' class="btn btn-success btn-quirk pull-right">Adicionar</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-inverse nomargin">
        <thead>
        <tr>
            <th>Setor</th>
            <th>Telefone</th>
            <th class="text-right">options</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $controllerSetor->exibirListar($setor);
        ?>
        </tbody>
    </table>
</div>
