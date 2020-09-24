<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:10
 */

namespace application\model\dao;


use application\model\bo\Mensagem;
use application\model\vo\Cargo;
use PDOException;

class CargoDAO {

    public function salvar(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("INSERT INTO cargo (cargo_nome) VALUES (:cargoNome)");
            $orm->bindValue(":cargoNome", $object->getCargoNome(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM cargo WHERE cargo_nome=?");
            $ormTester->execute(array($object->getCargoNome()));
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

    public function atualizar(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE cargo SET cargo_nome = :nome WHERE cargo_pk = :pk");
            $orm->bindValue(":pk", $object->getCargoPk(), Provider::PARAM_INT);
            $orm->bindValue(":nome", $object->getCargoNome(), Provider::PARAM_STR);

            $ormTester = $provider->prepare("SELECT * FROM cargo WHERE cargo_pk=?");
            $ormTester->execute(array($object->getCargoPk()));
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

    public function remover(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("DELETE FROM cargo WHERE cargo_pk = :pk");
            $orm->bindValue(":pk", $object->getCargoPk(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM cargo WHERE cargo_pk=?");
            $ormTester->execute(array($object->getCargoPk()));
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

    public function statusAtivarDesativar(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE cargo SET cargo_status = :cargoStatus WHERE cargo_pk = :pk");
            $orm->bindValue(":pk", $object->getCargoPk(), Provider::PARAM_INT);
            $orm->bindValue(":cargoStatus", $object->getCargoStatus(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM cargo WHERE cargo_pk=?");
            $ormTester->execute(array($object->getCargoPk()));
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

    public function exibirDetalhar(Cargo $object) {

    }

    public function exibirListar(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templatelistcargo.html");

        try {

            $orm = $provider->prepare("SELECT * FROM cargo WHERE cargo_status = 1");
            $orm->execute();
            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setCargoPk($ormListar['cargo_pk']);
                    $object->setCargoDtCriado($ormListar['cargo_dt_criado']);
                    $object->setCargoNome($ormListar['cargo_nome']);
                    $object->setCargoStatus($ormListar['cargo_status']);
                    print (
                    str_replace(
                        array(
                            "{nome}",
                            "{id}"
                        ),
                        array(
                            $object->getCargoNome(),
                            $object->getCargoPk()
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

    public function exibirFiltrarID(Cargo $object) {
    }

    public function exibirFiltrarNome(Cargo $object) {
    }

    public function exibirFiltrarTodos(Cargo $object) {
        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/combobox.html");

        try {

            $orm = $provider->prepare("SELECT * FROM cargo");
            $orm->execute();
            if ($orm->rowCount() >= 1):
                $cargo = "";
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setCargoPk($ormListar['cargo_pk']);
                    $object->setCargoDtCriado($ormListar['cargo_dt_criado']);
                    $object->setCargoNome($ormListar['cargo_nome']);
                    $object->setCargoStatus($ormListar['cargo_status']);
                    $cargo .=
                    str_replace(
                        array(
                            "{nome}",
                            "{id}"
                        ),
                        array(
                            $object->getCargoNome(),
                            $object->getCargoPk()
                        ),
                        $template

                    );
                }
                return $cargo;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function loadForm(Cargo $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templateformcargo.html");

        try {

            if (@$_REQUEST['id']) {
                $object->setCargoPk($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM cargo WHERE cargo_pk = :pk");
                $orm->bindValue(":pk", $object->getCargoPk(), Provider::PARAM_INT);
            } else {
                $orm = $provider->prepare("SELECT * FROM cargo");
            }

            $orm->execute();

            if ($orm->rowCount() == 1):
                $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                $object->setCargoPk($ormListar['cargo_pk']);
                $object->setCargoNome($ormListar['cargo_nome']);
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{id}",
                        "{nome}"
                    ),
                    array(
                        "cargo",
                        "editar",
                        $object->getCargoPk(),
                        $object->getCargoNome()
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
                        "{nome}"
                    ),
                    array(
                        "cargo",
                        "novo",
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