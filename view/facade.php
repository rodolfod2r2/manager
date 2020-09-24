<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 03/03/2018
 * Time: 00:10
 */

require_once('autoload.php');

function pathUri($uri) {
    if (empty($uri)) {
        $uri = null;
    }
    return $uri;
}

$opcao = pathUri(@$_REQUEST['view']);
$rota = pathUri(@$_REQUEST['rota']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('head.php'); ?>
</head>

<body>
<?php include_once('header.php'); ?>
<section>
    <?php include_once('leftpanel.php'); ?>
    <div class="mainpanel">
        <div id="list" class="contentpanel">
            <?php
            switch ($opcao) {
                case 'profile' :
                    switch ($rota) {
                        case 'editar':
                            include_once("pages/formprofile.php");
                            break;
                        default:
                            include_once("pages/profile.php");
                            break;
                    }
                    break;
                case 'cargo' :
                    switch ($rota) {
                        case 'novo':
                            include_once("pages/formcargo.php");
                            break;
                        case 'editar':
                            include_once("pages/formcargo.php");
                            break;
                        case 'remover':
                            include_once("pages/listarcargo.php");
                            break;
                        default:
                            include_once("pages/listarcargo.php");
                            break;
                    }
                    break;
                case 'setor' :
                    switch ($rota) {
                        case 'novo':
                            include_once("pages/formsetor.php");
                            break;
                        case 'editar':
                            include_once("pages/formsetor.php");
                            break;
                        case 'remover':
                            include_once("pages/listarsetor.php");
                            break;
                        default:
                            include_once("pages/listarsetor.php");
                            break;
                    }
                    break;
                case 'funcionario' :
                    switch ($rota) {
                        case 'novo':
                            include_once("pages/formfuncionario.php");
                            break;
                        case 'editar':
                            include_once("pages/formfuncionario.php");
                            break;
                        case 'remover':
                            include_once("pages/listarfuncionario.php");
                            break;
                        case 'pontoeletronico':
                            include_once("pages/listarpontoeletronico.php");
                            break;
                        default:
                            include_once("pages/listarfuncionario.php");
                            break;
                    }
                    break;
                case 'productowner' :
                    switch ($rota) {
                        case 'ativos':
                            include_once("pages/listarproductownerativo.php");
                            break;
                        case 'novo':
                            include_once("pages/formproductowner.php");
                            break;
                        case 'editar':
                            include_once("pages/formproductowner.php");
                            break;
                        case 'remover':
                            include_once("pages/listarproductowner.php");
                            break;
                        case 'relatorio':
                            include_once("pages/listarrelatorio.php");
                            break;
                        default:
                            include_once("pages/listarproductowner.php");
                            break;
                    }
                    break;
                case 'inventario' :
                    switch ($rota) {
                        case 'lista':
                            include_once("pages/listarinventario.php");
                            break;
                        case 'novo':
                            include_once("pages/forminventario.php");
                            break;
                        case 'editar':
                            include_once("pages/forminventario.php");
                            break;
                        case 'remover':
                            include_once("pages/listarproductowner.php");
                            break;
                        case 'relatorio':
                            include_once("pages/listarrelatorio.php");
                            break;
                        default:
                            include_once("pages/listarinventario.php");
                            break;
                    }
                    break;
                default:
                    echo 'Não Existe Relacionado';
                    break;
            }
            ?>
        </div>
    </div>
</section>
<?php include_once('footer.php'); ?>
</body>
</html>