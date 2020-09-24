<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 15:24
 */

use application\controller\ControllerCargo;
use application\controller\ControllerFuncionario;
use application\controller\ControllerHistorico;
use application\controller\ControllerInventario;
use application\controller\ControllerSetor;
use application\controller\Controllerproductowner;
use application\model\dao\Provider;
use application\model\vo\Cargo;
use application\model\vo\Endereco;
use application\model\vo\Funcionario;
use application\model\vo\Inventario;
use application\model\vo\Setor;
use application\model\vo\productowner;

require_once('autoload.php');

if (isset($_REQUEST['view']) || isset($_POST['view'])) {

    $view = $_REQUEST['view'];

    if (isset($_REQUEST['rota']) || isset($_POST['rota'])) {
        $rota = $_REQUEST['rota'];
    } else {
        $rota = "";
    }

    switch ($view) {
        case 'setor':
            $setor = new Setor();
            $controllerSetor = new ControllerSetor();
            switch ($rota) {
                case 'novo':
                    $setor->setSetorNome($_POST['nome']);
                    $setor->setSetorTelefone($_POST['telefone']);
                    $controllerSetor->salvar($setor);
                    break;
                case 'editar':
                    $setor->setSetorPK($_POST['id']);
                    $setor->setSetorNome($_REQUEST['nome']);
                    $setor->setSetorTelefone($_POST['telefone']);
                    $controllerSetor->atualizar($setor);
                    break;
                case 'ativar':
                    $setor->setSetorPK($_REQUEST['id']);
                    $setor->setSetorStatus(1);
                    $controllerSetor->statusAtivarDesativar($setor);
                    break;
                case 'desativar':
                    $setor->setSetorPK($_REQUEST['id']);
                    $setor->setSetorStatus(0);
                    $controllerSetor->statusAtivarDesativar($setor);
                    break;
                default:
                    echo "escolha uma acao";
                    break;
            }
            break;
        case 'cargo':
            $cargo = new Cargo();
            $controllerCargo = new ControllerCargo();
            switch ($rota) {
                case 'novo':
                    $cargo->setCargoNome($_POST['nome']);
                    $controllerCargo->salvar($cargo);
                    break;
                case 'editar':
                    $cargo->setCargoPk($_REQUEST['id']);
                    $cargo->setCargoNome($_POST['nome']);
                    $controllerCargo->atualizar($cargo);
                    break;
                case 'ativar':
                    $cargo->setCargoPk($_REQUEST['id']);
                    $cargo->setCargoStatus(1);
                    $controllerCargo->statusAtivarDesativar($cargo);
                    break;
                case 'desativar':
                    $cargo->setCargoPk($_REQUEST['id']);
                    $cargo->setCargoStatus(0);
                    $controllerCargo->statusAtivarDesativar($cargo);
                    break;
                default:
                    echo "escolha uma acao";
                    break;
            }
            break;
        case 'funcionario':
            $funcionario = new Funcionario();
            $endereco = new Endereco();
            $controllerFuncionario = new ControllerFuncionario();
            switch ($rota) {
                case 'novo':
                    $funcionario->setPessoaPK(Null);
                    if (isset($_POST['productowner_imagem']) && (!empty($_POST['productowner_imagem']))){
                        $funcionario->setPessoaImagem($_POST['productowner_imagem']);
                    } else {
                        $funcionario->setPessoaImagem(null);
                    }
                    $funcionario->setPessoaNome($_POST['nome']);
                    $funcionario->setPessoaDtNasc($_POST['dtnasc']);
                    $funcionario->setPessoaTelefone($_POST['telefone']);
                    $funcionario->setFuncionarioEmail($_POST['email']);
                    $funcionario->setFuncionarioSenha($_POST['senha']);
                    $funcionario->setFuncionarioNivelAcesso($_POST['nivel']);
                    $funcionario->setFuncionarioSetor(new Setor($_POST['setor']));
                    $funcionario->setFuncionarioCargo(new Cargo($_POST['cargo']));
                    $funcionario->setFuncionarioSalario($_POST['salario']);
                    $funcionario->setPessoaCPF($_POST['cpf']);
                    $funcionario->setPessoaRG($_POST['rg']);
                    $funcionario->setFuncionarioCTPS($_POST['ctps']);
                    $funcionario->setFuncionarioPisPasep($_POST['pis']);
                    $endereco->setCep($_POST['cep']);
                    $endereco->setLogradouro($_POST['logradouro']);
                    $endereco->setNumero($_POST['numero']);
                    $endereco->setBairro($_POST['bairro']);
                    $endereco->setCidade($_POST['cidade']);
                    $endereco->setEstado($_POST['estado']);
                    $funcionario->setPessoaEndereco($endereco);
                    $controllerFuncionario->salvar($funcionario);
                    break;
                case 'editar':
                    $funcionario->setPessoaPK($_POST['id']);
                    if (isset($_POST['productowner_imagem'])){
                        $funcionario->setPessoaImagem($_POST['productowner_imagem']);
                    } else {
                        $funcionario->setPessoaImagem(null);
                    }
                    $funcionario->setPessoaNome($_POST['nome']);
                    $funcionario->setPessoaDtNasc($_POST['dtnasc']);
                    $funcionario->setPessoaTelefone($_POST['telefone']);
                    $funcionario->setFuncionarioEmail($_POST['email']);
                    $funcionario->setFuncionarioSenha($_POST['senha']);
                    $funcionario->setFuncionarioNivelAcesso($_POST['nivel']);
                    $funcionario->setFuncionarioSetor(new Setor($_POST['setor']));
                    $funcionario->setFuncionarioCargo(new Cargo($_POST['cargo']));
                    $funcionario->setFuncionarioSalario($_POST['salario']);
                    $funcionario->setPessoaCPF($_POST['cpf']);
                    $funcionario->setPessoaRG($_POST['rg']);
                    $funcionario->setFuncionarioCTPS($_POST['ctps']);
                    $funcionario->setFuncionarioPisPasep($_POST['pis']);
                    $endereco->setCep($_POST['cep']);
                    $endereco->setLogradouro($_POST['logradouro']);
                    $endereco->setNumero($_POST['numero']);
                    $endereco->setBairro($_POST['bairro']);
                    $endereco->setCidade($_POST['cidade']);
                    $endereco->setEstado($_POST['estado']);
                    $funcionario->setPessoaEndereco($endereco);
                    $controllerFuncionario->atualizar($funcionario);
                    break;
                case 'ativar':
                    $funcionario->setPessoaPK($_REQUEST['id']);
                    $funcionario->setPessoaStatus(1);
                    $controllerFuncionario->statusAtivarDesativar($funcionario);
                    break;
                case 'remover':
                    $funcionario->setPessoaPK($_REQUEST['id']);
                    $funcionario->setPessoaStatus(0);
                    $controllerFuncionario->statusAtivarDesativar($funcionario);
                    break;
                case 'desativar':
                    $funcionario->setPessoaPK($_REQUEST['id']);
                    $funcionario->setPessoaStatus(0);
                    $controllerFuncionario->statusAtivarDesativar($funcionario);
                    break;
                case 'login':
                    $funcionario->setFuncionarioEmail($_POST['usuario']);
                    $funcionario->setFuncionarioSenha($_POST['senha']);
                    $controllerFuncionario->validarLogin($funcionario);
                    break;
                case 'logout':
                    $funcionario = new Funcionario();
                    $funcionario->setPessoaPK($id);
                    $object = new ControllerFuncionario();
                    $object->validarLogout($funcionario);
                    break;
                default:
                    echo "escolha uma acao";
                    break;
            }
            break;

        case 'profile':
            $funcionario = new Funcionario();
            $endereco = new Endereco();
            $controllerFuncionario = new ControllerFuncionario();
            switch ($rota) {
                case 'editar':
                    $funcionario->setPessoaPK($_POST['id']);
                    if (isset($_POST['productowner_imagem'])){
                        $funcionario->setPessoaImagem($_POST['productowner_imagem']);
                    } else {
                        $funcionario->setPessoaImagem(null);
                    }
                    $funcionario->setPessoaNome($_POST['nome']);
                    $funcionario->setPessoaDtNasc($_POST['dtnasc']);
                    $funcionario->setPessoaTelefone($_POST['telefone']);
                    $funcionario->setFuncionarioEmail($_POST['email']);
                    $funcionario->setFuncionarioSenha($_POST['senha']);
                    $funcionario->setFuncionarioNivelAcesso($_POST['nivel']);
                    $funcionario->setFuncionarioSetor(new Setor($_POST['setor']));
                    $funcionario->setFuncionarioCargo(new Cargo($_POST['cargo']));
                    $funcionario->setFuncionarioSalario($_POST['salario']);
                    $funcionario->setPessoaCPF($_POST['cpf']);
                    $funcionario->setPessoaRG($_POST['rg']);
                    $funcionario->setFuncionarioCTPS($_POST['ctps']);
                    $funcionario->setFuncionarioPisPasep($_POST['pis']);
                    $endereco->setCep($_POST['cep']);
                    $endereco->setLogradouro($_POST['logradouro']);
                    $endereco->setNumero($_POST['numero']);
                    $endereco->setBairro($_POST['bairro']);
                    $endereco->setCidade($_POST['cidade']);
                    $endereco->setEstado($_POST['estado']);
                    $funcionario->setPessoaEndereco($endereco);
                    $controllerFuncionario->atualizar($funcionario);
                    break;
                case 'ativar':
                    $funcionario->setPessoaPK($_REQUEST['id']);
                    $funcionario->setPessoaStatus(1);
                    $controllerFuncionario->statusAtivarDesativar($funcionario);
                    break;
                case 'desativar':
                    $funcionario->setPessoaPK($_REQUEST['id']);
                    $funcionario->setPessoaStatus(0);
                    $controllerFuncionario->statusAtivarDesativar($funcionario);
                    break;
                case 'login':
                    $funcionario->setFuncionarioEmail($_POST['usuario']);
                    $funcionario->setFuncionarioSenha($_POST['senha']);
                    $controllerFuncionario->validarLogin($funcionario);
                    break;
                case 'logout':
                    $funcionario = new Funcionario();
                    $funcionario->setPessoaPK($id);
                    $object = new ControllerFuncionario();
                    $object->validarLogout($funcionario);
                    break;
                default:
                    echo "escolha uma acao";
                    break;
            }
            break;
        case 'productowner':
            $setor = new Setor();
            $funcionario = new Funcionario();
            $productowner = new productowner();
            $controllerproductowner = new Controllerproductowner();
            $historico = new ControllerHistorico();

            switch ($rota) {
                case 'novo':
                    $productowner->setPessoaImagem(@$_POST['productowner_imagem']);
                    $productowner->setPessoaNome(@$_POST['nome']);
                    $productowner->setPessoaRG(@$_POST['rg']);
                    $productowner->setPessoaCPF(@$_POST['cpf']);
                    $productowner->setPessoaTelefone(@$_POST['telefone']);
                    $productowner->setproductownerTipo(@$_POST['tipo']);
                    $productowner->setPessoaEndereco(new Endereco(@$_POST['cep'], @$_POST['logradouro'], @$_POST['numero'], @$_POST['bairro'], @$_POST['cidade'],@$_POST['estado']));
                    $controllerproductowner->salvar($productowner);
                    $setor->setSetorPK($_POST['setor']);
                    $funcionario->setPessoaPK($_POST['funcionario']);
                    $provider = Provider::getProvider();
                    $lastID = $provider->lastInsertId();
                    $productowner->setPessoaPK($lastID);
                    $historico->ativarHistoricoproductowner($productowner, $funcionario, $setor);
                    break;
                case 'editar':
                    $productowner->setPessoaPK(@$_POST['id']);
                    $productowner->setPessoaImagem(@$_POST['productowner_imagem']);
                    $productowner->setPessoaNome(@$_POST['nome']);
                    $productowner->setPessoaRG(@$_POST['rg']);
                    $productowner->setPessoaCPF(@$_POST['cpf']);
                    $productowner->setPessoaTelefone(@$_POST['telefone']);
                    $productowner->setproductownerTipo(@$_POST['tipo']);
                    $productowner->setPessoaEndereco(new Endereco(@$_POST['cep'], @$_POST['logradouro'], @$_POST['numero'], @$_POST['bairro'], @$_POST['cidade'],@$_POST['estado']));
                    $controllerproductowner->atualizar($productowner);
                    break;
                case 'ativar':
                    $productowner->setPessoaPK($_POST['id']);
                    $productowner->setPessoaStatusAcesso(1);
                    $controllerproductowner->statusAtivarDesativar($productowner);
                    $setor->setSetorPK($_POST['setor']);
                    $funcionario->setPessoaPK($_POST['funcionario']);
                    $historico->ativarHistoricoproductowner($productowner, $funcionario, $setor);
                    break;
                case 'desativar':
                    $productowner->setPessoaPK($_POST['id']);
                    $productowner->setPessoaStatusAcesso(0);
                    $controllerproductowner->statusAtivarDesativar($productowner);
                    $historico->desativardHistoricoproductowner($_POST['idhistorico']);
                    break;
            }
            break;
        case 'inventario':
            $inventario = new Inventario();
            $controllerInventario = new ControllerInventario();
            switch ($rota) {
                case 'novo':
                    if (isset($_FILES['arquivo']) || !empty($_FILES['arquivo'])){
                        $inventario->setInventarioImagem(@$_FILES['arquivo']);
                    } else {
                        $inventario->setInventarioImagem(null);
                    }
                    $inventario->setInventarioCodigo(@$_POST['codigo']);
                    $inventario->setInventarioDescricao(@$_POST['nome']);
                    $inventario->setInventarioMarca(@$_POST['marca']);
                    $inventario->setInventarioModelo(@$_POST['modelo']);
                    $inventario->setInventarioNumeroSerie(@$_POST['numeroserie']);
                    $inventario->setInventarioDtAquisicao(@$_POST['dtaquisicao']);
                    $inventario->setInventarioDtGarantia(@$_POST['dtgarantia']);
                    $inventario->setInventarioValor(@$_POST['valor']);
                    $inventario->setInventarioTxDepreciacao(@$_POST['valordepreciacao']);
                    $inventario->setInventarioSetor(new Setor(@$_POST['setor']));
                    $inventario->setInventarioResponsavel(new Funcionario(@$_POST['funcionario']));
                    $controllerInventario->salvar($inventario);
                    break;
                case 'editar':
                    $inventario->setInventariopk(@$_POST['id']);
                    if (isset($_FILES['arquivo']) || !empty($_FILES['arquivo'])){
                        $inventario->setInventarioImagem(@$_FILES['arquivo']);
                    }
                    $inventario->setInventarioCodigo(@$_POST['codigo']);
                    $inventario->setInventarioDescricao(@$_POST['nome']);
                    $inventario->setInventarioMarca(@$_POST['marca']);
                    $inventario->setInventarioModelo(@$_POST['modelo']);
                    $inventario->setInventarioNumeroSerie(@$_POST['numeroserie']);
                    $inventario->setInventarioDtAquisicao(@$_POST['dtaquisicao']);
                    $inventario->setInventarioDtGarantia(@$_POST['dtgarantia']);
                    $inventario->setInventarioValor(@$_POST['valor']);
                    $inventario->setInventarioTxDepreciacao(@$_POST['valordepreciacao']);
                    $inventario->setInventarioSetor(new Setor(@$_POST['setor']));
                    $inventario->setInventarioResponsavel(new Funcionario(@$_POST['funcionario']));
                    $controllerInventario->atualizar($inventario);
                    break;
                case 'ativar':
                    break;
                case 'desativar':
                    $inventario->setInventariopk(@$_POST['id']);
                    $controllerInventario->statusAtivarDesativar($inventario);
                    break;
            }
            break;
        default:
            echo "none";
            break;
    }

}

