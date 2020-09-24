<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 03/03/2018
 * Time: 00:10
 */

use application\controller\ControllerHistorico;

$historico = new ControllerHistorico();


?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 people-list">
        <div class="people-options clearfix">
            <div class="btn-toolbar">
                <div class="col-sm-12 col-md-10 col-lg-12">
                    <div class="input-group mb0">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" id="pesquisar" placeholder="Pesquisar" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-inverse nomargin">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Responsavel</th>
            <th>Setor</th>
            <th>Entrada</th>
            <th>Saida</th>
        </tr>
        </thead>
        <tbody>
        <?php $historico->exibirListarproductowner(); ?>
        </tbody>
    </table>
</div>
