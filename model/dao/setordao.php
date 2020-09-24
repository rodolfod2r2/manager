<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:10
 */

namespace application\model\dao;


use application\model\bo\Mensagem;
use application\model\vo\Setor;
use PDOException;

class SetorDAO {

    public function salvar(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("INSERT INTO setor (setor_nome, setor_telefone) VALUES (:setorNome, :setorTelefone)");
            $orm->bindValue(":setorNome", $object->getSetorNome(), Provider::PARAM_STR);
            $orm->bindValue(":setorTelefone", $object->getSetorTelefone(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM setor WHERE setor_nome=?");
            $ormTester->execute(array($object->getSetorNome()));
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

    public function atualizar(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE setor SET setor_nome = :setorNome, setor_telefone = :setorTelefone WHERE setor_pk = :setorPK");
            $orm->bindValue(":setorPK", $object->getSetorPK(), Provider::PARAM_INT);
            $orm->bindValue(":setorNome", $object->getSetorNome(), Provider::PARAM_STR);
            $orm->bindValue(":setorTelefone", $object->getSetorTelefone(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM setor WHERE setor_pk=?");
            $ormTester->execute(array($object->getSetorPK()));
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

    public function remover(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("DELETE FROM setor WHERE setor_pk = :setorPK");
            $orm->bindValue(":setorPK", $object->getSetorPK(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM setor WHERE setor_pk=?");
            $ormTester->execute(array($object->getSetorPK()));
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

    public function statusAtivarDesativar(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE setor SET setor_status = :setorStatus WHERE setor_pk = :setorPK");
            $orm->bindValue(":setorPK", $object->getSetorPK(), Provider::PARAM_INT);
            $orm->bindValue(":setorStatus", $object->getSetorStatus(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM setor WHERE setor_pk=?");
            $ormTester->execute(array($object->getSetorPK()));
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


    public function exibirDetalhar(Setor $object) {
    }

    public function exibirListar(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("SELECT * FROM setor WHERE setor_status = 1");
            $orm->execute();

            if ($orm->rowCount() >= 1):

                $template = file_get_contents("template/templatelistsetor.html");

                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setSetorPK($ormListar['setor_pk']);
                    $object->setSetorDtCriado($ormListar['setor_dt_criado']);
                    $object->setSetorNome($ormListar['setor_nome']);
                    $object->setSetorTelefone($ormListar['setor_telefone']);
                    $object->setSetorStatus($ormListar['setor_status']);

                    print (
                    str_replace(
                        array(
                            "{nome}",
                            "{telefone}",
                            "{id}"
                        ),
                        array(
                            $object->getSetorNome(),
                            $object->getSetorTelefone(),
                            $object->getSetorPk()
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

    public function exibirFiltrarID(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/combobox.html");

        try {

            $orm = $provider->prepare("SELECT * FROM setor WHERE setor_status = 1 AND setor_pk = ?");
            $orm->execute(array($object->getSetorPK()));

            if ($orm->rowCount() == 1):
                $setor = "";
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setSetorPK($ormListar['setor_pk']);
                    $object->setSetorNome($ormListar['setor_nome']);
                    $setor .=
                        str_replace(
                            array(
                                "{nome}",
                                "{id}"
                            ),
                            array(
                                $object->getSetorNome(),
                                $object->getSetorPk()
                            ),
                            $template
                        );
                }
                return $setor;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function exibirFiltrarNome(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/itemsimple.html");

        try {

            $orm = $provider->prepare("SELECT * FROM setor WHERE setor_status = 1 AND setor_pk = ?");
            $orm->execute(array($object->getSetorPK()));

            if ($orm->rowCount() == 1):
                $setor = "";
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setSetorNome($ormListar['setor_nome']);
                    $setor .=
                        str_replace(
                            array(
                                "{nome}"
                            ),
                            array(
                                $object->getSetorNome()
                            ),
                            $template
                        );
                }
                return $setor;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function exibirFiltrarTodos(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/combobox.html");

        try {

            $orm = $provider->prepare("SELECT * FROM setor WHERE setor_status = 1");
            $orm->execute();

            if ($orm->rowCount() >= 1):
                $setor = "";
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setSetorPK($ormListar['setor_pk']);
                    $object->setSetorNome($ormListar['setor_nome']);
                    $setor .=
                        str_replace(
                            array(
                                "{nome}",
                                "{id}"
                            ),
                            array(
                                $object->getSetorNome(),
                                $object->getSetorPk()
                            ),
                            $template
                        );
                }
                return $setor;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function loadForm(Setor $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templateformsetor.html");

        try {

            if (@$_REQUEST['id']) {
                $object->setSetorPk($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM setor WHERE setor_pk = :setorPK");
                $orm->bindValue(":setorPK", $object->getSetorPK(), Provider::PARAM_INT);
            } else {
                $orm = $provider->prepare("SELECT * FROM setor");
            }

            $orm->execute();

            if ($orm->rowCount() == 1):
                $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                $object->setSetorPk($ormListar['setor_pk']);
                $object->setSetorNome($ormListar['setor_nome']);
                $object->setSetorTelefone($ormListar['setor_telefone']);
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{id}",
                        "{nome}",
                        "{telefone}"
                    ),
                    array(
                        "setor",
                        "editar",
                        $object->getSetorPk(),
                        $object->getSetorNome(),
                        $object->getSetorTelefone()
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
                        "{id}",
                        "{nome}",
                        "{telefone}"
                    ),
                    array(
                        "setor",
                        "novo",
                        "",
                        "",
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