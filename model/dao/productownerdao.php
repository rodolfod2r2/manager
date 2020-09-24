<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:09
 */

namespace application\model\dao;

use application\controller\ControllerFuncionario;
use application\controller\ControllerHistorico;
use application\controller\ControllerSetor;
use application\model\bo\Mensagem;
use application\model\bo\SEO;
use application\model\bo\Upload;
use application\model\vo\Endereco;
use application\model\vo\Funcionario;
use application\model\vo\Setor;
use application\model\vo\productowner;
use PDOException;

class productownerDAO {

    public function salvar(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {

            $orm = $provider->prepare(
                "INSERT INTO productowner (
                  productowner_imagem,
                  productowner_nome,
                  productowner_doc_rg,
                  productowner_doc_cpf,
                  productowner_telefone,
                  productowner_tipo,         
                  productowner_cep,
                  productowner_logradouro,
                  productowner_numero,
                  productowner_bairro,
                  productowner_cidade,
                  productowner_estado
                ) VALUES (
                  :imagem,
                  :nome,
                  :rg,
                  :cpf,
                  :fone,
                  :tipo,
                  :cep,
                  :logradouro,
                  :numero,
                  :bairro,
                  :cidade,
                  :estado
                )");

            $upload->uploadBaseCode($object->getPessoaImagem(), $seo->urlAmigavel($object->getPessoaNome()));

            $orm->bindValue(":imagem", ($seo->urlAmigavel($object->getPessoaNome()).'.jpg'), Provider::PARAM_STR);
            $orm->bindValue(":nome", $object->getPessoaNome(), Provider::PARAM_STR);
            $orm->bindValue(":rg", $object->getPessoaRG(), Provider::PARAM_STR);
            $orm->bindValue(":cpf", $object->getPessoaCPF(), Provider::PARAM_STR);
            $orm->bindValue(":fone", $object->getPessoaTelefone(), Provider::PARAM_STR);
            $orm->bindValue(":tipo", $object->getproductownerTipo(), Provider::PARAM_STR);
            $orm->bindValue(":cep", $object->getPessoaEndereco()->getCep(), Provider::PARAM_STR);
            $orm->bindValue(":logradouro", $object->getPessoaEndereco()->getLogradouro(), Provider::PARAM_STR);
            $orm->bindValue(":numero", $object->getPessoaEndereco()->getNumero(), Provider::PARAM_STR);
            $orm->bindValue(":bairro", $object->getPessoaEndereco()->getBairro(), Provider::PARAM_STR);
            $orm->bindValue(":cidade", $object->getPessoaEndereco()->getCidade(), Provider::PARAM_STR);
            $orm->bindValue(":estado", $object->getPessoaEndereco()->getEstado(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM productowner WHERE productowner_nome=?");
            $ormTester->execute(array($object->getPessoaNome()));
            if ($ormTester->rowCount() == 0):
                $orm->execute();
                $mensagem->setResponse($mensagem::SUCESSO_SALVAR);
            else :
                $mensagem->setResponse($mensagem::AVISO_SALVAR);
            endif;

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function atualizar(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {

            $orm = $provider->prepare(
                    "UPDATE productowner 
                      SET 
                      productowner_imagem = :imagem, 
                      productowner_nome = :nome, 
                      productowner_doc_rg = :rg, 
                      productowner_doc_cpf = :cpf, 
                      productowner_telefone = :fone, 
                      productowner_tipo = :tipo,        
                      productowner_cep = :cep, 
                      productowner_logradouro = :logradouro, 
                      productowner_numero = :numero, 
                      productowner_bairro = :bairro, 
                      productowner_cidade = :cidade, 
                      productowner_estado = :estado
                      WHERE productowner_pk = :pk");

            $upload->uploadBaseCode($object->getPessoaImagem(), $seo->urlAmigavel($object->getPessoaNome()));

            $orm->bindValue(":pk", $object->getPessoaPK(), Provider::PARAM_INT);
            $orm->bindValue(":imagem", ($seo->urlAmigavel($object->getPessoaNome()).'.jpg'), Provider::PARAM_STR);
            $orm->bindValue(":nome", $object->getPessoaNome(), Provider::PARAM_STR);
            $orm->bindValue(":rg", $object->getPessoaRG(), Provider::PARAM_STR);
            $orm->bindValue(":cpf", $object->getPessoaCPF(), Provider::PARAM_STR);
            $orm->bindValue(":fone", $object->getPessoaTelefone(), Provider::PARAM_STR);
            $orm->bindValue(":tipo", $object->getproductownerTipo(), Provider::PARAM_STR);
            $orm->bindValue(":cep", $object->getPessoaEndereco()->getCep(), Provider::PARAM_STR);
            $orm->bindValue(":logradouro", $object->getPessoaEndereco()->getLogradouro(), Provider::PARAM_STR);
            $orm->bindValue(":numero", $object->getPessoaEndereco()->getNumero(), Provider::PARAM_STR);
            $orm->bindValue(":bairro", $object->getPessoaEndereco()->getBairro(), Provider::PARAM_STR);
            $orm->bindValue(":cidade", $object->getPessoaEndereco()->getCidade(), Provider::PARAM_STR);
            $orm->bindValue(":estado", $object->getPessoaEndereco()->getEstado(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM productowner WHERE productowner_pk=?");
            $ormTester->execute(array($object->getPessoaPK()));
            if ($ormTester->rowCount() == 1):
                $orm->execute();
                $mensagem->setResponse($mensagem::SUCESSO_ATUALIZAR);
            else :
                $mensagem->setResponse($mensagem::AVISO_ATUALIZAR);
            endif;

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function remover(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("DELETE FROM productowner WHERE productowner_pk = :productownerPK");
            $orm->bindValue(":productownerPK", $object->getPessoaPK(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM productowner WHERE productowner_pk=?");
            $ormTester->execute(array($object->getPessoaPK()));
            if ($ormTester->rowCount() == 1):
                $orm->execute();
                $mensagem->setResponse($mensagem::SUCESSO_REMOVER);
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function statusAtivarDesativar(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE productowner SET productowner_status_acesso = :productownerStatusAcesso WHERE productowner_pk = :productownerPK");
            $orm->bindValue(":productownerPK", $object->getPessoaPK(), Provider::PARAM_INT);
            $orm->bindValue(":productownerStatusAcesso", $object->getPessoaStatusAcesso(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM productowner WHERE productowner_pk=?");
            $ormTester->execute(array($object->getPessoaPK()));
            if ($ormTester->rowCount() == 1):
                $orm->execute();
                $mensagem->setResponse($mensagem::SUCESSO_ATUALIZAR);
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function exibirDetalhar(productowner $object) {

    }

    public function exibirListar(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();
        $template = file_get_contents("template/templatelistproductowner.html");

        try {

            $orm = $provider->prepare("SELECT * FROM productowner WHERE productowner_status_acesso = 0");
            $orm->execute();

            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['productowner_pk']);
                    $object->setPessoaDtCriado($ormListar['productowner_dt_criado']);
                    $object->setPessoaImagem($ormListar['productowner_imagem']);
                    $object->setPessoaNome($ormListar['productowner_nome']);
                    $object->setPessoaTelefone($ormListar['productowner_telefone']);
                    $object->setPessoaRG($ormListar['productowner_doc_rg']);
                    $object->setPessoaCPF($ormListar['productowner_doc_cpf']);
                    $object->setproductownerTipo($ormListar['productowner_tipo']);
                    $object->setPessoaStatusAcesso($ormListar['productowner_status_acesso']);
                    $object->setPessoaEndereco(new Endereco($ormListar['productowner_cep'], $ormListar['productowner_logradouro'], $ormListar['productowner_numero'], $ormListar['productowner_bairro'], $ormListar['productowner_cidade'], $ormListar['productowner_estado']));
                    if ($object->getPessoaStatusAcesso() == 0) {
                        $btn = "Ativar";
                    } else {
                        $btn = "Desativar";
                    }
                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{imagem}",
                            "{nome}",
                            "{tipo}",
                            "{botao}",
                            "{setor}",
                            "{responsavel}",
                            "{id}"
                        ),
                        array(
                            "productowner",
                            "ativar",
                            $object->getPessoaImagem(),
                            $object->getPessoaNome(),
                            $object->getproductownerTipo(),
                            $btn,
                            $controllerSetor->exibirFiltrarTodos($setor),
                            $controllerFuncionario->exibirFiltrarTodos($funcionario),
                            $object->getPessoaPK()
                        ),
                        $template
                    )
                    );
                }
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function exibirFiltrarID(productowner $object) {

    }

    public function exibirFiltrarNome(productowner $object) {

    }

    public function exibirFiltrarTodos(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templatelistproductownerativo.html");

        try {

            $orm = $provider->prepare(
                "SELECT * FROM productowner AS visi
                    INNER JOIN historico_productowner AS hv ON hv.productowner_fk  = visi.productowner_pk
                    INNER JOIN setor AS st ON  st.setor_pk = hv.historico_productowner_setor
                    INNER JOIN funcionario AS fc ON  fc.funcionario_pk = hv.historico_productowner_responsavel
                    WHERE hv.historico_productowner_status_acesso = :productownerStatusAcesso
                    "
            );

            $orm->bindValue(":productownerStatusAcesso", $object->getPessoaStatusAcesso(), Provider::PARAM_INT);
            $orm->execute();

            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['productowner_pk']);
                    $object->setPessoaDtCriado($ormListar['productowner_dt_criado']);
                    $object->setPessoaImagem($ormListar['productowner_imagem']);
                    $object->setPessoaNome($ormListar['productowner_nome']);
                    $object->setPessoaTelefone($ormListar['productowner_telefone']);
                    $object->setPessoaRG($ormListar['productowner_doc_rg']);
                    $object->setPessoaCPF($ormListar['productowner_doc_cpf']);
                    $object->setproductownerTipo($ormListar['productowner_tipo']);
                    $object->setPessoaStatusAcesso($ormListar['productowner_status_acesso']);
                    $object->setPessoaEndereco(new Endereco($ormListar['productowner_cep'], $ormListar['productowner_logradouro'], $ormListar['productowner_numero'], $ormListar['productowner_bairro'], $ormListar['productowner_cidade'], $ormListar['productowner_estado']));
                    $setor = $ormListar['setor_nome'];
                    $funcionario = $ormListar['funcionario_nome'];
                    $idhistorico = $ormListar['historico_productowner_pk'];
                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{imagem}",
                            "{nome}",
                            "{tipo}",
                            "{botao}",
                            "{id}",
                            "{setor}",
                            "{responsavel}",
                            "{idhistorico}"
                        ),
                        array(
                            "productowner",
                            "desativar",
                            $object->getPessoaImagem(),
                            $object->getPessoaNome(),
                            $object->getproductownerTipo(),
                            "Desativar",
                            $object->getPessoaPK(),
                            $setor, $funcionario , $idhistorico
                        ),
                        $template
                    )
                    );
                }
            else :
                $mensagem->setResponse($mensagem::SEM_REGISTRO);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function contarAtivos() {
        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        try {
            $listar = $provider->prepare("SELECT * FROM productowner WHERE productowner_status_acesso = 1");
            $listar->execute();
            $mensagem->setResponse($listar->rowCount());
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            return $mensagem->getResponse();
        }
    }

    public function loadForm(productowner $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();



        try {

            if (@$_REQUEST['id']) {
                $object->setPessoaPK($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM productowner WHERE productowner_pk = :productownerPK");
                $orm->bindValue(":productownerPK", $object->getPessoaPK(), Provider::PARAM_INT);
                $template = file_get_contents("template/templateformproductownersimple.html");

            } else {
                $orm = $provider->prepare("SELECT * FROM productowner");
                $template = file_get_contents("template/templateformproductowner.html");
            }

            $orm->execute();

            if ($orm->rowCount() == 1):
                $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                $object->setPessoaPK($ormListar['productowner_pk']);
                $object->setPessoaDtCriado($ormListar['productowner_dt_criado']);
                $object->setPessoaImagem($ormListar['productowner_imagem']);
                $object->setPessoaNome($ormListar['productowner_nome']);
                $object->setPessoaTelefone($ormListar['productowner_telefone']);
                $object->setPessoaRG($ormListar['productowner_doc_rg']);
                $object->setPessoaCPF($ormListar['productowner_doc_cpf']);
                $object->setproductownerTipo($ormListar['productowner_tipo']);
                $object->setPessoaStatusAcesso($ormListar['productowner_status_acesso']);
                $object->setPessoaEndereco(new Endereco($ormListar['productowner_cep'], $ormListar['productowner_logradouro'], $ormListar['productowner_numero'], $ormListar['productowner_bairro'], $ormListar['productowner_cidade'], $ormListar['productowner_estado']));
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{imagem}",
                        "{nome}",
                        "{cpf}",
                        "{rg}",
                        "{telefone}",
                        "{tipo}",
                        "{cep}",
                        "{logradouro}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{setor}",
                        "{responsavel}",
                        "{id}"
                    ),
                    array(
                        "productowner",
                        "editar",
                        $object->getPessoaImagem(),
                        $object->getPessoaNome(),
                        $object->getPessoaCPF(),
                        $object->getPessoaRG(),
                        $object->getPessoaTelefone(),
                        $object->getproductownerTipo(),
                        $object->getPessoaEndereco()->getCep(),
                        $object->getPessoaEndereco()->getLogradouro(),
                        $object->getPessoaEndereco()->getNumero(),
                        $object->getPessoaEndereco()->getBairro(),
                        $object->getPessoaEndereco()->getCidade(),
                        $object->getPessoaEndereco()->getEstado(),
                        $controllerSetor->exibirFiltrarTodos($setor),
                        $controllerFuncionario->exibirFiltrarTodos($funcionario),
                        $object->getPessoaPK()
                    ),
                    $template
                )
                );
            else :
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{imagem}",
                        "{nome}",
                        "{cpf}",
                        "{rg}",
                        "{telefone}",
                        "{tipo}",
                        "{cep}",
                        "{logradouro}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{setor}",
                        "{responsavel}",
                        "{id}"
                    ),
                    array(
                        "productowner",
                        "novo",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        $controllerSetor->exibirFiltrarTodos($setor),
                        $controllerFuncionario->exibirFiltrarTodos($funcionario),
                        ""
                    ),
                    $template
                )
                );
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

}