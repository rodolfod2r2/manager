<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:12
 */

namespace application\model\dao;


use application\model\bo\Mensagem;
use application\model\vo\Funcionario;
use application\model\vo\HistoricoFuncionario;
use application\model\vo\Historicoproductowner;
use application\model\vo\Setor;
use application\model\vo\productowner;
use PDOException;

class HistoricoDAO {

    public function ativarHistoricoFuncionario(Funcionario $object) {
        $provider = Provider::getProvider();
        $historico = new HistoricoFuncionario();
        $historico->setHistoricoFuncionarioFK($object->getPessoaPK());
        $historico->setHistoricoStatusAcesso(1);
        $inserir = $provider->prepare(
            "INSERT INTO historico_funcionario (
                  historico_funcionario_status_acesso,
                  funcionario_fk
                ) VALUES (
                  :historicoStatusAcesso,
                  :funcionarioFK
                )"
        );
        $inserir->bindValue(":historicoStatusAcesso", $historico->getHistoricoStatusAcesso(), Provider::PARAM_INT);
        $inserir->bindValue(":funcionarioFK", $historico->getHistoricoFuncionarioFK(), Provider::PARAM_INT);
        $inserir->execute();
    }

    public function desativardHistoricoFuncionario(HistoricoFuncionario $historico) {
        $provider = Provider::getProvider();
        $historico->setHistoricoPK($historico->getHistoricoPK());
        $historico->setHistoricoStatusAcesso(0);
        $atualizar = $provider->prepare(
            "UPDATE historico_funcionario SET 
                  historico_funcionario_status_acesso     = :historicoStatusAcesso
                  WHERE historico_funcionario_pk   = :historicoPK 
                "
        );
        $atualizar->bindValue(":historicoStatusAcesso", $historico->getHistoricoStatusAcesso(), Provider::PARAM_STR);
        $atualizar->bindValue(":historicoPK", $historico->getHistoricoPK(), Provider::PARAM_INT);
        $atualizar->execute();
    }

    public function exibirListarPonto() {

        $provider = Provider::getProvider();
        $funcionario = new productowner();
        $historico = new HistoricoFuncionario();
        $mensagem = new Mensagem();

        $template = file_get_contents("template/templatelisthistoricofuncionario.html");


        try {

            $orm = $provider->prepare(
                "SELECT * FROM funcionario AS funci
                    INNER JOIN historico_funcionario AS hv ON hv.funcionario_fk  = funci.funcionario_pk ORDER BY historico_funcionario_dt_login DESC
                    "
            );

            $orm->execute();
            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $funcionario->setPessoaPK($ormListar['funcionario_pk']);
                    $funcionario->setPessoaDtCriado($ormListar['funcionario_dt_criado']);
                    $funcionario->setPessoaImagem($ormListar['funcionario_imagem']);
                    $funcionario->setPessoaNome($ormListar['funcionario_nome']);
                    $idhistorico = $ormListar['historico_funcionario_pk'];
                    $entrada = $ormListar['historico_funcionario_dt_login'];
                    $saida = $ormListar['historico_funcionario_dt_logoff'];

                    if ($saida > $entrada):
                        $esaida = $saida;
                    else:
                        $esaida = "";
                    endif;

                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{nome}",
                            "{entrada}",
                            "{saida}"
                        ),
                        array(
                            "funcionario",
                            "relatorio",
                            $funcionario->getPessoaNome(),
                            $entrada, $esaida
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

    public function exibirListarproductowner() {

        $provider = Provider::getProvider();
        $productowner = new productowner();
        $historico = new Historicoproductowner();
        $mensagem = new Mensagem();

        $template = file_get_contents("template/templatelisthistoricoproductowner.html");


        try {

            $orm = $provider->prepare(
                "SELECT * FROM productowner AS visi
                    INNER JOIN historico_productowner AS hv ON hv.productowner_fk  = visi.productowner_pk
                    INNER JOIN setor AS st ON  st.setor_pk = hv.historico_productowner_setor
                    INNER JOIN funcionario AS fc ON  fc.funcionario_pk = hv.historico_productowner_responsavel
                    "
            );

            $orm->execute();
            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $productowner->setPessoaPK($ormListar['productowner_pk']);
                    $productowner->setPessoaDtCriado($ormListar['productowner_dt_criado']);
                    $productowner->setPessoaImagem($ormListar['productowner_imagem']);
                    $productowner->setPessoaNome($ormListar['productowner_nome']);
                    $setor = $ormListar['setor_nome'];
                    $funcionario = $ormListar['funcionario_nome'];
                    $idhistorico = $ormListar['historico_productowner_pk'];
                    $entrada = $ormListar['historico_productowner_dt_entrada'];
                    $saida = $ormListar['historico_productowner_dt_saida'];

                    if ($saida > $entrada):
                        $esaida = $saida;
                    else:
                        $esaida = "";
                    endif;

                    print (
                    str_replace(
                        array(
                            "{view}",
                            "{rota}",
                            "{nome}",
                            "{id}",
                            "{setor}",
                            "{responsavel}",
                            "{entrada}",
                            "{saida}"
                        ),
                        array(
                            "productowner",
                            "relatorio",
                            $productowner->getPessoaNome(),
                            $productowner->getPessoaPK(),
                            $setor, $funcionario, $entrada, $esaida
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

    public function ativarHistoricoproductowner(productowner $productowner, Funcionario $funcionario, Setor $setor) {

        $provider = Provider::getProvider();
        $historico = new Historicoproductowner();
        $historico->setHistoricoSetor($setor->getSetorPK());
        $historico->setHistoricoResponsavel($funcionario->getPessoaPK());
        $historico->setHistoricoproductownerFK($productowner->getPessoaPK());
        $historico->setHistoricoStatusAcesso(1);
        $inserir = $provider->prepare(
            "INSERT INTO historico_productowner (
                  historico_productowner_setor,
                  historico_productowner_responsavel,
                  historico_productowner_status_acesso,
                  productowner_fk
                ) VALUES (
                  :historicoSetor,
                  :historicoResponsavel,
                  :historicoStatusAcesso,
                  :productownerFK
                )"
        );
        $inserir->bindValue(":historicoSetor", $historico->getHistoricoSetor(), Provider::PARAM_STR);
        $inserir->bindValue(":historicoResponsavel", $historico->getHistoricoResponsavel(), Provider::PARAM_STR);
        $inserir->bindValue(":historicoStatusAcesso", $historico->getHistoricoStatusAcesso(), Provider::PARAM_STR);
        $inserir->bindValue(":productownerFK", $historico->getHistoricoproductownerFK(), Provider::PARAM_INT);
        $inserir->execute();

    }

    public function desativardHistoricoproductowner($historicoID) {

        $provider = Provider::getProvider();
        $historico = new Historicoproductowner();
        $historico->setHistoricoPK($historicoID);
        $historico->setHistoricoStatusAcesso(0);
        $atualizar = $provider->prepare(
            "UPDATE historico_productowner SET 
                  historico_productowner_status_acesso     = :historicoproductownerStatusAcesso
                  WHERE historico_productowner_pk   = :historicoproductownerPk 
                "
        );
        $atualizar->bindValue(":historicoproductownerStatusAcesso", $historico->getHistoricoStatusAcesso(), Provider::PARAM_INT);
        $atualizar->bindValue(":historicoproductownerPk", $historico->getHistoricoPK(), Provider::PARAM_INT);
        $atualizar->execute();

    }

    public function desativarHistoricoproductownerFuncionario() {

        $provider = Provider::getProvider();
        $atualizarproductowner = $provider->prepare("UPDATE productowner SET productowner_status_acesso = 0 WHERE productowner_status_acesso = 1 ");
        $atualizarproductowner->execute();
        $atualizarHistoricoproductowner = $provider->prepare("UPDATE historico_productowner SET historico_productowner_status_acesso = 0 WHERE historico_productowner_status_acesso = 1 ");
        $atualizarHistoricoproductowner->execute();
        $atualizarFuncionario = $provider->prepare("UPDATE funcionario SET funcionario_status_acesso = 0 WHERE funcionario_status_acesso = 1 ");
        $atualizarFuncionario->execute();
        $atualizarHistoricoFuncionario = $provider->prepare("UPDATE historico_funcionario SET historico_funcionario_status_acesso = 0 WHERE historico_funcionario_status_acesso = 1 ");
        $atualizarHistoricoFuncionario->execute();

    }

}