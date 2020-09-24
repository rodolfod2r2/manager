<?php

use application\controller\ControllerFuncionario;

require_once('autoload.php');

$controllerFuncionario = new ControllerFuncionario();
$controllerFuncionario->verificaLogin();
$id = $_SESSION['id'];
$nome = $_SESSION['usuario'];
$foto = $_SESSION['foto'];
$nivel = $_SESSION['nivel'];


?>
<div class="leftpanel">
    <div class="leftpanelinner">
        <div class="media leftpanel-profile">
            <div class="media-left">
                <img src="../resources/image/funcionario/<?php echo $foto; ?>" alt="" class="media-object img-circle">
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href="../profile">
                        <small><?php echo $nome; ?></small>
                    </a></h4>
            </div>
        </div>
        <ul class="nav nav-pills nav-stacked nav-quirk">
            <?php if ($nivel < 4) { ?>
                <li><a href="../productowner/ativos"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
                <li class="nav-parent">
                    <a href=""><i class="fa fa-cubes"></i> <span>Product Owner</span></a>
                    <ul class="children">
                        <li><a href="../productowner">Listar Product Owner</a></li>
                        <li><a href="../productowner/novo">Adicionar productowner</a></li>
                        <li><a href="../productowner/relatorio">Relatório productowner</a></li>
                    </ul>
                </li>
                <li class="nav-parent">
                    <a href=""><i class="fa fa-cubes"></i> <span>Team</span></a>
                    <ul class="children">
                        <li><a href="../funcionario">Listar Team</a></li>
                        <li><a href="../funcionario/novo">Adicionar Funcionário</a></li>
                        <li><a href="../funcionario/pontoeletronico">Ponto Eletrônico</a></li>
                    </ul>
                </li>
                <li class="nav-parent">
                    <a href=""><i class="fa fa-cubes"></i> <span>Inventário</span></a>
                    <ul class="children">
                        <li><a href="../inventario">Catálogo de Inventário</a></li>
                        <li><a href="../inventario/novo">Adicionar Inventário</a></li>
                    </ul>
                </li>
                <!--            <li><a href="../productowner/relatorio"><i class="fa fa-file-text"></i> <span>Relatórios</span></a>-->
                </li>
                <li class="nav-parent">
                    <a href=""><i class="fa fa-cubes"></i> <span>Configurações</span></a>
                    <ul class="children">
                        <li><a href="../cargo">Cargo</a></li>
                        <li><a href="../setor">Setor</a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($nivel == 10) { ?>
            <li class="nav-parent">
                <a href=""><i class="fa fa-cubes"></i> <span>Inventário</span></a>
                <ul class="children">
                    <li><a href="../inventario">Catálogo de Inventário</a></li>
                    <li><a href="../inventario/novo">Adicionar Inventário</a></li>
                </ul>
            </li>
            <?php } ?>
            <li><a href="dispatcher.php?view=funcionario&rota=logout"><i class="fa fa-sign-out"></i><span>Sair</span></a></li>
        </ul>
    </div>
</div>