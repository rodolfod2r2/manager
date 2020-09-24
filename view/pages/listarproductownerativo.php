<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 03/03/2018
 * Time: 00:10
 */

use application\controller\Controllerproductowner;
use application\model\vo\productowner;


$productowner = new productowner();
$productowner->setPessoaStatusAcesso(1);
$controllerproductowner = new Controllerproductowner();
$valor = $controllerproductowner->contarAtivos();

?>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel">
                <div class="panel-heading">
                    <h4 class="panel-title">productowners</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="h2 nomargin paddingtop10"><?php echo $valor ?> Ativo(s)</div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="pull-right nomargin"><a href="../productowner" class="btn btn-primary btn-quirk">Catálogo
                                    de productowners <i class="fa fa-arrow-right"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if ($controllerproductowner->contarAtivos() > 0) { ?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 people-list">
            <div class="people-options clearfix">
                <div class="btn-toolbar">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group mb0">
                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            <input type="text" id='pesquisar' placeholder="Pesquisar" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <?php $controllerproductowner->exibirFiltrarTodos($productowner); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>