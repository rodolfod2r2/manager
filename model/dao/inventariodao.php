<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 23/03/2018
 * Time: 11:06
 */

namespace application\model\dao;

use application\controller\ControllerFuncionario;
use application\controller\ControllerSetor;
use application\model\bo\Mensagem;
use application\model\bo\SEO;
use application\model\bo\Upload;
use application\model\vo\Funcionario;
use application\model\vo\Inventario;
use application\model\vo\Setor;
use PDOException;

class InventarioDAO {

    public function salvar(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {
            $orm = $provider->prepare(
                "INSERT INTO inventario (
                    inventario_imagem, 
                    inventario_codigo, 
                    inventario_descricao, 
                    inventario_marca, 
                    inventario_modelo, 
                    inventario_numeroserie, 
                    inventario_dtaquisicao, 
                    inventario_garantia, 
                    inventario_valor, 
                    inventario_txdrepreciacao, 
                    inventario_setor, 
                    inventario_funcionario
                ) VALUES (
                    :inventarioImagem, 
                    :inventarioCodigo, 
                    :inventarioDescricao, 
                    :inventarioMarca, 
                    :inventarioModelo, 
                    :inventarioNumeroSerie, 
                    :inventarioDtAquisicao, 
                    :inventarioGarantia, 
                    :inventarioValor, 
                    :inventarioTxdepreciacao, 
                    :inventarioSetor, 
                    :inventarioFuncionario
                )"
            );

            if ($object->getInventarioImagem() !== null) {
                $imagem = $object->getInventarioImagem()['name'];
                $nome = explode(strrchr($imagem, "."), $imagem);
                $novonome = ($seo->urlAmigavel($nome[0]));
                $ext = strrchr($imagem, '.');
                $orm->bindValue(":inventarioImagem", strtolower($novonome . $ext), Provider::PARAM_STR);
                $upload->uploadSimples(strtolower($object->getInventarioImagem()['name']), $object->getInventarioImagem()['tmp_name']);
            } else {
                $orm->bindValue(":inventarioImagem", 'demo.jpg', Provider::PARAM_STR);
            }

            $orm->bindValue(":inventarioCodigo", $object->getInventarioCodigo(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioDescricao", $object->getInventarioDescricao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioMarca", $object->getInventarioMarca(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioModelo", $object->getInventarioModelo(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioNumeroSerie", $object->getInventarioNumeroSerie(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioDtAquisicao", $object->getInventarioDtAquisicao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioGarantia", $object->getInventarioDtGarantia(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioValor", str_replace(',','.',str_replace('.','',$object->getInventarioValor())), Provider::PARAM_STR);
            $orm->bindValue(":inventarioTxdepreciacao", $object->getInventarioTxDepreciacao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioSetor", $object->getInventarioSetor()->getSetorPK(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioFuncionario", $object->getInventarioResponsavel()->getPessoaPK(), Provider::PARAM_STR);


            $ormTester = $provider->prepare("SELECT * FROM inventario WHERE inventario_descricao=?");
            $ormTester->execute(array($object->getInventarioDescricao()));
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

    public function atualizar(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {
            if ($object->getInventarioImagem()['name'] == ""):
                $orm = $provider->prepare("
                    UPDATE inventario SET 
                        inventario_codigo = :inventarioCodigo, 
                        inventario_descricao = :inventarioDescricao, 
                        inventario_marca = :inventarioMarca, 
                        inventario_modelo = :inventarioModelo, 
                        inventario_numeroserie = :inventarioNumeroSerie, 
                        inventario_dtaquisicao = :inventarioDtAquisicao, 
                        inventario_garantia = :inventarioGarantia, 
                        inventario_valor = :inventarioValor, 
                        inventario_txdrepreciacao = :inventarioTxdepreciacao, 
                        inventario_setor = :inventarioSetor, 
                        inventario_funcionario = :inventarioFuncionario
                    WHERE inventario_pk = :pk"
                );
            else:
                $orm = $provider->prepare("
                    UPDATE inventario SET 
                        inventario_imagem = :inventarioImagem, 
                        inventario_codigo = :inventarioCodigo, 
                        inventario_descricao = :inventarioDescricao, 
                        inventario_marca = :inventarioMarca, 
                        inventario_modelo = :inventarioModelo, 
                        inventario_numeroserie = :inventarioNumeroSerie, 
                        inventario_dtaquisicao = :inventarioDtAquisicao, 
                        inventario_garantia = :inventarioGarantia, 
                        inventario_valor = :inventarioValor, 
                        inventario_txdrepreciacao = :inventarioTxdepreciacao, 
                        inventario_setor = :inventarioSetor, 
                        inventario_funcionario = :inventarioFuncionario
                    WHERE inventario_pk = :pk"
                );

                $imagem = $object->getInventarioImagem()['name'];
                $nome = @explode(strrchr($imagem, "."), $imagem);
                $novonome = ($seo->urlAmigavel($nome[0]));
                $ext = strrchr($imagem, '.');
                $orm->bindValue(":inventarioImagem", strtolower($novonome . $ext), Provider::PARAM_STR);
                $upload->uploadSimples(strtolower($object->getInventarioImagem()['name']), $object->getInventarioImagem()['tmp_name']);
            endif;

            $orm->bindValue(":pk", $object->getInventariopk(), Provider::PARAM_INT);
            $orm->bindValue(":inventarioCodigo", $object->getInventarioCodigo(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioDescricao", $object->getInventarioDescricao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioMarca", $object->getInventarioMarca(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioModelo", $object->getInventarioModelo(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioNumeroSerie", $object->getInventarioNumeroSerie(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioDtAquisicao", $object->getInventarioDtAquisicao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioGarantia", $object->getInventarioDtGarantia(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioValor", str_replace(',','.',str_replace('.','',$object->getInventarioValor())), Provider::PARAM_STR);
            $orm->bindValue(":inventarioTxdepreciacao", $object->getInventarioTxDepreciacao(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioSetor", $object->getInventarioSetor()->getSetorPK(), Provider::PARAM_STR);
            $orm->bindValue(":inventarioFuncionario", $object->getInventarioResponsavel()->getPessoaPK(), Provider::PARAM_STR);

            $orm->execute();
            $mensagem->setResponse($mensagem::SUCESSO_ATUALIZAR);

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function remover(Inventario $object) {

    }

    public function statusAtivarDesativar(Inventario $object) {
        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE inventario SET inventario_status = 0 WHERE inventario_pk = :inventario_PK");
            $orm->bindValue(":inventario_PK", $object->getInventariopk(), Provider::PARAM_INT);
            $orm->execute();

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function exibirDetalhar(Inventario $object) {

    }

    public function exibirListar(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $seo = new SEO();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();

        $template = file_get_contents("template/templatelistinventario.html");


        try {

            $orm = $provider->prepare("SELECT * FROM inventario AS inv
                    INNER JOIN setor AS st ON inv.inventario_setor = st.setor_pk
                    INNER JOIN funcionario AS func ON inv.inventario_funcionario = func.funcionario_pk
                    WHERE inv.inventario_status = 1
            ");

            $orm->execute();

            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setInventariopk(htmlentities($ormListar['inventario_pk']));
                    $object->setInventarioImagem(htmlentities($ormListar['inventario_imagem']));
                    $object->setInventarioCodigo(htmlentities($ormListar['inventario_codigo']));
                    $object->setInventarioDescricao(htmlentities($ormListar['inventario_descricao']));
                    $object->setInventarioMarca(htmlentities($ormListar['inventario_marca']));
                    $object->setInventarioModelo(htmlentities($ormListar['inventario_modelo']));
                    $object->setInventarioNumeroSerie(htmlentities($ormListar['inventario_numeroserie']));
                    $object->setInventarioDtAquisicao(htmlentities($ormListar['inventario_dtaquisicao']));
                    $object->setInventarioDtGarantia(htmlentities($ormListar['inventario_garantia']));
                    $object->setInventarioValor(htmlentities($ormListar['inventario_valor']));
                    $object->setInventarioTxDepreciacao(htmlentities($ormListar['inventario_txdrepreciacao']));
                    $object->setInventarioSetor(new Setor(htmlentities($ormListar['inventario_setor'])));
                    $object->setInventarioResponsavel(new Funcionario(htmlentities($ormListar['inventario_funcionario'])));
                    $object->setInventarioStatus(htmlentities($ormListar['inventario_status']));
                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{imagem}",
                            "{codigo}",
                            "{descricao}",
                            "{marca}",
                            "{modelo}",
                            "{numeroserie}",
                            "{dtaquisicao}",
                            "{dtgarantia}",
                            "{valor}",
                            "{valordepreciacao}",
                            "{IDsetor}",
                            "{IDresponsavel}",
                            "{id}"
                        ),
                        array(
                            "inventario",
                            "editar",
                            $object->getInventarioImagem(),
                            $object->getInventarioCodigo(),
                            $object->getInventarioDescricao(),
                            $object->getInventarioMarca(),
                            $object->getInventarioModelo(),
                            $object->getInventarioNumeroSerie(),
                            $object->getInventarioDtAquisicao(),
                            $object->getInventarioDtGarantia(),
                            number_format($object->getInventarioValor(),2,',','.'),
                            $object->getInventarioTxDepreciacao(),
                            $controllerSetor->exibirFiltrarNome($object->getInventarioSetor()),
                            $controllerFuncionario->exibirFiltrarNome($object->getInventarioResponsavel()),
                            $object->getInventariopk()
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

    public function exibirFiltrarID(Inventario $object) {

    }

    public function exibirFiltrarNome(Inventario $object) {

    }

    public function exibirFiltrarTodos(Inventario $object) {

    }

    public function contarAtivos() {

    }

    public function loadForm(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();

        $template = file_get_contents("template/templateforminventario.html");

        try {
            if (@$_REQUEST['id']) :
                $object->setInventariopk($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM inventario WHERE inventario_pk = :inventarioPK");
                $orm->bindValue(":inventarioPK", $object->getInventariopk(), Provider::PARAM_INT);
                $orm->execute();
                if ($orm->rowCount() == 1):
                    $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                    $object->setInventariopk(htmlentities($ormListar['inventario_pk']));
                    $object->setInventarioImagem(htmlentities($ormListar['inventario_imagem']));
                    $object->setInventarioCodigo(htmlentities($ormListar['inventario_codigo']));
                    $object->setInventarioDescricao(htmlentities($ormListar['inventario_descricao']));
                    $object->setInventarioMarca(htmlentities($ormListar['inventario_marca']));
                    $object->setInventarioModelo(htmlentities($ormListar['inventario_modelo']));
                    $object->setInventarioNumeroSerie(htmlentities($ormListar['inventario_numeroserie']));
                    $object->setInventarioDtAquisicao(htmlentities($ormListar['inventario_dtaquisicao']));
                    $object->setInventarioDtGarantia(htmlentities($ormListar['inventario_garantia']));
                    $object->setInventarioValor(htmlentities($ormListar['inventario_valor']));
                    $object->setInventarioTxDepreciacao(htmlentities($ormListar['inventario_txdrepreciacao']));
                    $object->setInventarioSetor(new Setor(htmlentities($ormListar['inventario_setor'])));
                    $object->setInventarioResponsavel(new Funcionario(htmlentities($ormListar['inventario_funcionario'])));
                    $object->setInventarioStatus(htmlentities($ormListar['inventario_status']));
                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{imagem}",
                            "{codigo}",
                            "{descricao}",
                            "{marca}",
                            "{modelo}",
                            "{numeroserie}",
                            "{dtaquisicao}",
                            "{dtgarantia}",
                            "{valor}",
                            "{valordepreciacao}",
                            "{IDsetor}",
                            "{setor}",
                            "{IDresponsavel}",
                            "{responsavel}",
                            "{id}"
                        ),
                        array(
                            "inventario",
                            "editar",
                            $object->getInventarioImagem(),
                            $object->getInventarioCodigo(),
                            $object->getInventarioDescricao(),
                            $object->getInventarioMarca(),
                            $object->getInventarioModelo(),
                            $object->getInventarioNumeroSerie(),
                            $object->getInventarioDtAquisicao(),
                            $object->getInventarioDtGarantia(),
                            number_format($object->getInventarioValor(),2,',','.'),
                            $object->getInventarioTxDepreciacao(),
                            $controllerSetor->exibirFiltrarID($object->getInventarioSetor()),
                            $controllerSetor->exibirFiltrarTodos($setor),
                            $controllerFuncionario->exibirFiltrarID($object->getInventarioResponsavel()),
                            $controllerFuncionario->exibirFiltrarTodos($funcionario),
                            $object->getInventariopk()
                        ),
                        $template
                    )
                    );
                endif;
            else:
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{imagem}",
                        "{codigo}",
                        "{descricao}",
                        "{marca}",
                        "{modelo}",
                        "{numeroserie}",
                        "{dtaquisicao}",
                        "{dtgarantia}",
                        "{valor}",
                        "{valordepreciacao}",
                        "{IDsetor}",
                        "{setor}",
                        "{IDresponsavel}",
                        "{responsavel}",
                        "{id}"
                    ),
                    array(
                        "inventario",
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
                        $controllerSetor->exibirFiltrarTodos($setor),
                        "",
                        $controllerFuncionario->exibirFiltrarTodos($funcionario),
                        ""
                    ),
                    $template
                )
                );
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        }
    }


    public function InventarioTotal(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $seo = new SEO();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();

        $template = file_get_contents("template/templatelistinventariototal.html");

        try {

            $orm = $provider->prepare("SELECT * FROM inventario AS inv
                    INNER JOIN setor AS st ON inv.inventario_setor = st.setor_pk
                    INNER JOIN funcionario AS func ON inv.inventario_funcionario = func.funcionario_pk
                    WHERE inv.inventario_status = 1
            ");

            $orm->execute();

            $valor = 0;
            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setInventarioValor(htmlentities($ormListar['inventario_valor']));
                    $valor = $valor + $object->getInventarioValor();

                }

                print (
                    str_replace(
                        array(
                            "{valor}"
                        ),
                        array(
                            number_format($valor,2,',','.')
                        ),
                        $template
                    )
                );

            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function InventarioTotalSetor(Inventario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $seo = new SEO();
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $funcionario = new Funcionario();
        $controllerFuncionario = new ControllerFuncionario();

        $template = file_get_contents("template/templatelistinventariototalsetor.html");

        try {

            $orm = $provider->prepare("
                SELECT inventario_setor, inventario_status, SUM(`inventario_valor`) as total FROM inventario AS inv
                INNER JOIN setor AS st ON inv.inventario_setor = st.setor_pk
                WHERE inv.inventario_status = 1 GROUP BY inv.inventario_setor
            ");

            $orm->execute();

            $valor = 0;
            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setInventarioValor(htmlentities($ormListar['total']));
                    $object->setInventarioSetor(new Setor(htmlentities($ormListar['inventario_setor'])));
                    $valor = $valor + $object->getInventarioValor();
                    print (
                    str_replace(
                        array(
                            "{valor}",
                            "{setor}"
                        ),
                        array(
                            number_format($valor,2,',','.'),
                            $controllerSetor->exibirFiltrarNome($object->getInventarioSetor())
                        ),
                        $template
                    )
                    );
                    $valor= 0;
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

}