<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:10
 */

namespace application\model\dao;


use application\controller\ControllerCargo;
use application\controller\ControllerFuncionario;
use application\controller\ControllerHistorico;
use application\controller\ControllerSetor;
use application\model\bo\Mensagem;
use application\model\bo\SEO;
use application\model\bo\Upload;
use application\model\vo\Cargo;
use application\model\vo\Endereco;
use application\model\vo\Funcionario;
use application\model\vo\HistoricoFuncionario;
use application\model\vo\Setor;
use PDOException;

class FuncionarioDAO {

    public function salvar(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {

            $orm = $provider->prepare(
                "INSERT INTO funcionario (
                  funcionario_imagem,
                  funcionario_nome,
                  funcionario_dtnasc,
                  funcionario_email,
                  funcionario_senha,
                  funcionario_telefone,
                  funcionario_setor,
                  funcionario_cargo,
                  funcionario_salario,
                  funcionario_cpf,
                  funcionario_rg,
                  funcionario_ctps,
                  funcionario_pispasep,
                  funcionario_cep,
                  funcionario_logradouro,
                  funcionario_numero,
                  funcionario_bairro,
                  funcionario_cidade,
                  funcionario_estado,
                  funcionario_nivel
                ) VALUES (
                    :funcionarioImagem,
                    :funcionarioNome,
                    :funcionarioDtNasc,
                    :funcionarioEmail,
                    :funcionarioSenha,
                    :funcionarioTelefone,
                    :funcionarioSetor,
                    :funcionarioCargo,
                    :funcionarioSalario,
                    :funcionarioCPF,
                    :funcionarioRG,
                    :funcionarioCTPS,
                    :funcionarioPisPasep,
                    :funcionarioCEP,
                    :funcionarioLogradouro,
                    :funcionarioNumero,
                    :funcionarioBairro,
                    :funcionarioCidade,
                    :funcionarioEstado,
                    :funcionarioNivel
                )"
            );

            if ($object->getPessoaImagem() !== null ) {
                $orm->bindValue(":funcionarioImagem", ($seo->urlAmigavel($object->getPessoaNome()).'.jpg'), Provider::PARAM_STR);
                $upload->uploadBaseCodeF($object->getPessoaImagem(), $seo->urlAmigavel($object->getPessoaNome()));
            } else {
                $orm->bindValue(":funcionarioImagem", 'demo.jpg', Provider::PARAM_STR);
            }


            $orm->bindValue(":funcionarioNome", $object->getPessoaNome(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioDtNasc", $object->getPessoaDtNasc(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioEmail", $object->getFuncionarioEmail(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioSenha", $object->getFuncionarioSenha(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioTelefone", $object->getPessoaTelefone(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioSetor", $object->getFuncionarioSetor()->getSetorPK(), Provider::PARAM_INT);
            $orm->bindValue(":funcionarioCargo", $object->getFuncionarioCargo()->getCargoPk(), Provider::PARAM_INT);
            $orm->bindValue(":funcionarioSalario", $object->getFuncionarioSalario(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioCPF", $object->getPessoaCPF(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioRG", $object->getPessoaRG(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioCTPS", $object->getFuncionarioCTPS(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioPisPasep", $object->getFuncionarioPisPasep(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioCEP", $object->getPessoaEndereco()->getCep(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioLogradouro", $object->getPessoaEndereco()->getLogradouro(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioNumero", $object->getPessoaEndereco()->getNumero(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioBairro", $object->getPessoaEndereco()->getBairro(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioCidade", $object->getPessoaEndereco()->getCidade(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioEstado", $object->getPessoaEndereco()->getEstado(), Provider::PARAM_STR);
            $orm->bindValue(":funcionarioNivel", $object->getFuncionarioNivelAcesso(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_nome=?");
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

    public function atualizar(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $upload = new Upload();
        $seo = new SEO();

        try {

            if ($object->getPessoaImagem() == null):
                $orm = $provider->prepare("
                    UPDATE funcionario SET 
                        funcionario_nome = :nome, 
                        funcionario_dtnasc = :nascimento,
                        funcionario_email  = :email,
                        funcionario_senha = :senha,
                        funcionario_telefone = :telefone,
                        funcionario_setor  = :setor,
                        funcionario_cargo  = :cargo,
                        funcionario_salario  = :salario,
                        funcionario_cpf  = :cpf,
                        funcionario_rg  = :rg,
                        funcionario_ctps  = :ctps,
                        funcionario_pispasep  = :pis,
                        funcionario_cep  = :cep,
                        funcionario_logradouro  = :logradouro,
                        funcionario_numero  = :numero,
                        funcionario_bairro  = :bairro,
                        funcionario_cidade  = :cidade,
                        funcionario_estado  = :estado,
                        funcionario_nivel  = :nivel 
                    WHERE funcionario_pk = :pk"
                );
            else:
                $orm = $provider->prepare("
                    UPDATE funcionario SET 
                        funcionario_imagem = :imagem,
                        funcionario_nome = :nome, 
                        funcionario_dtnasc = :nascimento,
                        funcionario_email  = :email,
                        funcionario_senha = :senha,
                        funcionario_telefone = :telefone,
                        funcionario_setor  = :setor,
                        funcionario_cargo  = :cargo,
                        funcionario_salario  = :salario,
                        funcionario_cpf  = :cpf,
                        funcionario_rg  = :rg,
                        funcionario_ctps  = :ctps,
                        funcionario_pispasep  = :pis,
                        funcionario_cep  = :cep,
                        funcionario_logradouro  = :logradouro,
                        funcionario_numero  = :numero,
                        funcionario_bairro  = :bairro,
                        funcionario_cidade  = :cidade,
                        funcionario_estado  = :estado,
                        funcionario_nivel  = :nivel 
                    WHERE funcionario_pk = :pk"
                );

                $upload->uploadBaseCodeF($object->getPessoaImagem(), $seo->urlAmigavel($object->getPessoaNome()));
                $orm->bindValue(":imagem", ($seo->urlAmigavel($object->getPessoaNome()).'.jpg'), Provider::PARAM_STR);
            endif;


            $orm->bindValue(":pk", $object->getPessoaPK(), Provider::PARAM_INT);
            $orm->bindValue(":nome", $object->getPessoaNome(), Provider::PARAM_STR);
            $orm->bindValue(":nascimento", $object->getPessoaDtNasc(), Provider::PARAM_STR);
            $orm->bindValue(":email", $object->getFuncionarioEmail(), Provider::PARAM_STR);
            $orm->bindValue(":senha", $object->getFuncionarioSenha(), Provider::PARAM_STR);
            $orm->bindValue(":telefone", $object->getPessoaTelefone(), Provider::PARAM_STR);
            $orm->bindValue(":setor", $object->getFuncionarioSetor()->getSetorPK(), Provider::PARAM_INT);
            $orm->bindValue(":cargo", $object->getFuncionarioCargo()->getCargoPk(), Provider::PARAM_INT);
            $orm->bindValue(":salario", $object->getFuncionarioSalario(), Provider::PARAM_INT);
            $orm->bindValue(":cpf", $object->getPessoaCPF(), Provider::PARAM_STR);
            $orm->bindValue(":rg", $object->getPessoaRG(), Provider::PARAM_STR);
            $orm->bindValue(":ctps", $object->getFuncionarioCTPS(), Provider::PARAM_STR);
            $orm->bindValue(":pis", $object->getFuncionarioPisPasep(), Provider::PARAM_STR);
            $orm->bindValue(":cep", $object->getPessoaEndereco()->getCep(), Provider::PARAM_STR);
            $orm->bindValue(":logradouro", $object->getPessoaEndereco()->getLogradouro(), Provider::PARAM_STR);
            $orm->bindValue(":numero", $object->getPessoaEndereco()->getNumero(), Provider::PARAM_STR);
            $orm->bindValue(":bairro", $object->getPessoaEndereco()->getBairro(), Provider::PARAM_STR);
            $orm->bindValue(":cidade", $object->getPessoaEndereco()->getCidade(), Provider::PARAM_STR);
            $orm->bindValue(":estado", $object->getPessoaEndereco()->getEstado(), Provider::PARAM_STR);
            $orm->bindValue(":nivel", $object->getFuncionarioNivelAcesso(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_pk=?");
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

    public function remover(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("DELETE FROM funcionario WHERE funcionario_pk = :funcionarioPK");
            $orm->bindValue(":funcionarioPK", $object->getPessoaPK(), Provider::PARAM_INT);

            $ormTester = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_pk=?");
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

    public function statusAtivarDesativar(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();

        try {

            $orm = $provider->prepare("UPDATE funcionario SET funcionario_status_acesso = :funcionarioStatusAcesso WHERE funcionario_pk = :funcionarioPK");
            $orm->bindValue(":funcionarioPK", $object->getPessoaPK(), Provider::PARAM_INT);
            $orm->bindValue(":funcionarioStatusAcesso", $object->getPessoaStatusAcesso(), Provider::PARAM_INT);
            $orm->execute();

        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function exibirDetalhar(Funcionario $object) {
        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $seo = new SEO();
        $template = file_get_contents("template/templateprofile.html");

        try {
            $orm = $provider->prepare("SELECT * FROM funcionario AS funci
                    INNER JOIN setor AS st ON funci.funcionario_setor = st.setor_pk
                    INNER JOIN cargo AS cr ON funci.funcionario_cargo = cr.cargo_pk
                    WHERE funci.funcionario_nivel >= 0 AND funci.funcionario_pk = ?;
            ");

            $orm->execute(array($object->getPessoaPK()));

            if ($orm->rowCount() == 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['funcionario_pk']);
                    $object->setPessoaDtCriado($ormListar['funcionario_dt_criado']);
                    $object->setPessoaImagem($ormListar['funcionario_imagem']);
                    $object->setPessoaNome($ormListar['funcionario_nome']);
                    $object->setFuncionarioEmail($ormListar['funcionario_email']);
                    $object->setFuncionarioSenha($ormListar['funcionario_senha']);
                    $object->setPessoaTelefone($ormListar['funcionario_telefone']);
                    $object->setPessoaDtNasc($ormListar['funcionario_dtnasc']);
                    $object->setPessoaRG($ormListar['funcionario_rg']);
                    $object->setPessoaCPF($ormListar['funcionario_cpf']);
                    $object->setFuncionarioCTPS($ormListar['funcionario_ctps']);
                    $object->setFuncionarioPisPasep($ormListar['funcionario_pispasep']);
                    $object->setFuncionarioSalario($ormListar['funcionario_salario']);
                    $object->setFuncionarioNivelAcesso($ormListar['funcionario_nivel']);
                    $object->setFuncionarioCargo(new Cargo($ormListar['cargo_pk'], $ormListar['cargo_dt_criado'], $ormListar['cargo_nome'], $ormListar['cargo_status']));
                    $object->setFuncionarioSetor(new Setor($ormListar['setor_pk'], $ormListar['setor_dt_criado'], $ormListar['setor_nome'], $ormListar['setor_telefone'], $ormListar['setor_status']));
                    $object->setPessoaEndereco(new Endereco($ormListar['funcionario_cep'], $ormListar['funcionario_logradouro'], $ormListar['funcionario_numero'], $ormListar['funcionario_bairro'], $ormListar['funcionario_cidade'], $ormListar['funcionario_estado']));
                    switch ($object->getFuncionarioNivelAcesso()) {
                        case 0 :
                            $acesso = "DEVOPS";
                            break;
                        case 1 :
                            $acesso = "Diretoria";
                            break;
                        case 2 :
                            $acesso = "Administração";
                            break;
                        case 3 :
                            $acesso = "Recepção";
                            break;
                        case 4 :
                            $acesso = "Funcionários";
                            break;
                        default:
                            $acesso = "";
                            break;
                    }
                    print (
                    str_replace(
                        array(
                            "{imagem}",
                            "{nome}",
                            "{cargo}",
                            "{setor}",
                            "{telefonesetor}",
                            "{dtnasc}",
                            "{docrg}",
                            "{doccpf}",
                            "{docctps}",
                            "{docpispasep}",
                            "{salario}",
                            "{email}",
                            "{senha}",
                            "{telefone}",
                            "{nivel}",
                            "{cep}",
                            "{rua}",
                            "{numero}",
                            "{bairro}",
                            "{cidade}",
                            "{estado}",
                            "{id}"
                        ),
                        array(
                            $object->getPessoaImagem(),
                            $object->getPessoaNome(),
                            $object->getFuncionarioCargo()->getCargoNome(),
                            $object->getFuncionarioSetor()->getSetorNome(),
                            $object->getFuncionarioSetor()->getSetorTelefone(),
                            $object->getPessoaDtNasc(),
                            $object->getPessoaRG(),
                            $object->getPessoaCPF(),
                            $object->getFuncionarioCTPS(),
                            $object->getFuncionarioPisPasep(),
                            $object->getFuncionarioSalario(),
                            $object->getFuncionarioEmail(),
                            $seo->cryptPass($object->getFuncionarioSenha(), 8),
                            $object->getPessoaTelefone(),
                            $acesso,
                            $object->getPessoaEndereco()->getCep(),
                            $object->getPessoaEndereco()->getLogradouro(),
                            $object->getPessoaEndereco()->getNumero(),
                            $object->getPessoaEndereco()->getBairro(),
                            $object->getPessoaEndereco()->getCidade(),
                            $object->getPessoaEndereco()->getEstado(),
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

    public function exibirListar(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $seo = new SEO();
        $template = file_get_contents("template/templatelistfuncionario.html");

        try {

            $orm = $provider->prepare("SELECT * FROM funcionario AS funci
                    INNER JOIN setor AS st ON funci.funcionario_setor = st.setor_pk
                    INNER JOIN cargo AS cr ON funci.funcionario_cargo = cr.cargo_pk
                    WHERE funci.funcionario_nivel > 0 AND funci.funcionario_status = 1;
            ");

            $orm->execute();

            if ($orm->rowCount() >= 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['funcionario_pk']);
                    $object->setPessoaDtCriado($ormListar['funcionario_dt_criado']);
                    $object->setPessoaImagem($ormListar['funcionario_imagem']);
                    $object->setPessoaNome($ormListar['funcionario_nome']);
                    $object->setFuncionarioEmail($ormListar['funcionario_email']);
                    $object->setFuncionarioSenha($ormListar['funcionario_senha']);
                    $object->setPessoaTelefone($ormListar['funcionario_telefone']);
                    $object->setPessoaDtNasc($ormListar['funcionario_dtnasc']);
                    $object->setPessoaRG($ormListar['funcionario_rg']);
                    $object->setPessoaCPF($ormListar['funcionario_cpf']);
                    $object->setFuncionarioCTPS($ormListar['funcionario_ctps']);
                    $object->setFuncionarioPisPasep($ormListar['funcionario_pispasep']);
                    $object->setFuncionarioSalario($ormListar['funcionario_salario']);
                    $object->setFuncionarioNivelAcesso($ormListar['funcionario_nivel']);
                    $object->setFuncionarioCargo(new Cargo($ormListar['cargo_pk'], $ormListar['cargo_dt_criado'], $ormListar['cargo_nome'], $ormListar['cargo_status']));
                    $object->setFuncionarioSetor(new Setor($ormListar['setor_pk'], $ormListar['setor_dt_criado'], $ormListar['setor_nome'], $ormListar['setor_telefone'], $ormListar['setor_status']));
                    $object->setPessoaEndereco(new Endereco($ormListar['funcionario_cep'], $ormListar['funcionario_logradouro'], $ormListar['funcionario_numero'], $ormListar['funcionario_bairro'], $ormListar['funcionario_cidade'], $ormListar['funcionario_estado']));
                    switch ($object->getFuncionarioNivelAcesso()) {
                        case 0 :
                            $acesso = "DEVOPS";
                            break;
                        case 1 :
                            $acesso = "Diretoria";
                            break;
                        case 2 :
                            $acesso = "Administração";
                            break;
                        case 3 :
                            $acesso = "Recepção";
                            break;
                        case 4 :
                            $acesso = "Funcionários";
                            break;
                        default:
                            $acesso = "";
                            break;
                    }
                    print (
                    str_replace(
                        array(
                            "{imagem}",
                            "{nome}",
                            "{cargo}",
                            "{setor}",
                            "{telefonesetor}",
                            "{dtnasc}",
                            "{docrg}",
                            "{doccpf}",
                            "{docctps}",
                            "{docpispasep}",
                            "{salario}",
                            "{email}",
                            "{senha}",
                            "{telefone}",
                            "{nivel}",
                            "{cep}",
                            "{rua}",
                            "{numero}",
                            "{bairro}",
                            "{cidade}",
                            "{estado}",
                            "{id}"
                        ),
                        array(
                            $object->getPessoaImagem(),
                            $object->getPessoaNome(),
                            $object->getFuncionarioCargo()->getCargoNome(),
                            $object->getFuncionarioSetor()->getSetorNome(),
                            $object->getFuncionarioSetor()->getSetorTelefone(),
                            $object->getPessoaDtNasc(),
                            $object->getPessoaRG(),
                            $object->getPessoaCPF(),
                            $object->getFuncionarioCTPS(),
                            $object->getFuncionarioPisPasep(),
                            $object->getFuncionarioSalario(),
                            $object->getFuncionarioEmail(),
                            $seo->cryptPass($object->getFuncionarioSenha(), 8),
                            $object->getPessoaTelefone(),
                            $acesso,
                            $object->getPessoaEndereco()->getCep(),
                            $object->getPessoaEndereco()->getLogradouro(),
                            $object->getPessoaEndereco()->getNumero(),
                            $object->getPessoaEndereco()->getBairro(),
                            $object->getPessoaEndereco()->getCidade(),
                            $object->getPessoaEndereco()->getEstado(),
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

    public function exibirFiltrarID(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/combobox.html");

        try {
            $orm = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_status = 1 AND funcionario_nivel > 0 AND funcionario_pk = ?");
            $orm->execute(array($object->getPessoaPK()));
            if ($orm->rowCount() == 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['funcionario_pk']);
                    $object->setPessoaNome($ormListar['funcionario_nome']);
                    $funcionario =
                        str_replace(
                            array(
                                "{nome}",
                                "{id}"
                            ),
                            array(
                                $object->getPessoaNome(),
                                $object->getPessoaPK()
                            ),
                            $template
                        );
                }
                return $funcionario;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }

    }

    public function exibirFiltrarNome(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/itemsimple.html");

        try {
            $orm = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_status = 1 AND funcionario_nivel > 0 AND funcionario_pk = ?");
            $orm->execute(array($object->getPessoaPK()));
            if ($orm->rowCount() == 1):
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['funcionario_pk']);
                    $object->setPessoaNome($ormListar['funcionario_nome']);
                    $funcionario =
                        str_replace(
                            array(
                                "{nome}"
                            ),
                            array(
                                $object->getPessoaNome()
                            ),
                            $template
                        );
                }
                return $funcionario;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }


    }

    public function exibirFiltrarTodos(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/combobox.html");

        try {
            $orm = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_status = 1 AND funcionario_nivel > 0");
            $orm->execute();
            if ($orm->rowCount() >= 1):
                $funcionario = '';
                while ($ormListar = $orm->fetch(Provider::FETCH_ASSOC)) {
                    $object->setPessoaPK($ormListar['funcionario_pk']);
                    $object->setPessoaNome($ormListar['funcionario_nome']);
                    $funcionario .=
                        str_replace(
                            array(
                                "{nome}",
                                "{id}"
                            ),
                            array(
                                $object->getPessoaNome(),
                                $object->getPessoaPK()
                            ),
                            $template
                        );
                }
                return $funcionario;
            else :
                $mensagem->setResponse($mensagem::AVISO_EXIBIR);
            endif;
        } catch (PDOException $exception) {
            $mensagem->setResponse("Erro: " . $mensagem::AVISO . "CODE" . $exception->getMessage());
        } finally {
            echo $mensagem->getResponse();
        }
    }

    public function loadForm(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templateformfuncionario.html");
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $cargo = new Cargo();
        $controllerCargo = new ControllerCargo();

        try {

            if (@$_REQUEST['id']) {
                $object->setPessoaPK($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM funcionario AS funci
                    INNER JOIN setor AS st ON funci.funcionario_setor = st.setor_pk
                    INNER JOIN cargo AS cr ON funci.funcionario_cargo = cr.cargo_pk WHERE funcionario_pk = :funcionarioPK");
                $orm->bindValue(":funcionarioPK", $object->getPessoaPK(), Provider::PARAM_INT);
            } else {
                $orm = $provider->prepare("SELECT * FROM funcionario AS funci
                    INNER JOIN setor AS st ON funci.funcionario_setor = st.setor_pk
                    INNER JOIN cargo AS cr ON funci.funcionario_cargo = cr.cargo_pk");
            }

            $orm->execute();

            if ($orm->rowCount() == 1):
                $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                $object->setPessoaPK($ormListar['funcionario_pk']);
                $object->setPessoaDtCriado($ormListar['funcionario_dt_criado']);
                $object->setPessoaImagem($ormListar['funcionario_imagem']);
                $object->setPessoaNome($ormListar['funcionario_nome']);
                $object->setFuncionarioEmail($ormListar['funcionario_email']);
                $object->setFuncionarioSenha($ormListar['funcionario_senha']);
                $object->setPessoaTelefone($ormListar['funcionario_telefone']);
                $object->setPessoaDtNasc($ormListar['funcionario_dtnasc']);
                $object->setPessoaRG($ormListar['funcionario_rg']);
                $object->setPessoaCPF($ormListar['funcionario_cpf']);
                $object->setFuncionarioCTPS($ormListar['funcionario_ctps']);
                $object->setFuncionarioPisPasep($ormListar['funcionario_pispasep']);
                $object->setFuncionarioSalario($ormListar['funcionario_salario']);
                $object->setFuncionarioNivelAcesso($ormListar['funcionario_nivel']);
                $object->setFuncionarioCargo(new Cargo($ormListar['cargo_pk'], $ormListar['cargo_dt_criado'], $ormListar['cargo_nome'], $ormListar['cargo_status']));
                $object->setFuncionarioSetor(new Setor($ormListar['setor_pk'], $ormListar['setor_dt_criado'], $ormListar['setor_nome'], $ormListar['setor_telefone'], $ormListar['setor_status']));
                $object->setPessoaEndereco(new Endereco($ormListar['funcionario_cep'], $ormListar['funcionario_logradouro'], $ormListar['funcionario_numero'], $ormListar['funcionario_bairro'], $ormListar['funcionario_cidade'], $ormListar['funcionario_estado']));

                switch ($object->getFuncionarioNivelAcesso()) {
                    case 0 :
                        $acesso = "DEVOPS";
                        break;
                    case 1 :
                        $acesso = "Diretoria";
                        break;
                    case 2 :
                        $acesso = "Administração";
                        break;
                    case 3 :
                        $acesso = "Recepção";
                        break;
                    case 4 :
                        $acesso = "Funcionários";
                        break;
                    default:
                        $acesso = "";
                        break;
                }
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{imagem}",
                        "{nome}",
                        "{idcargo}",
                        "{cargo}",
                        "{listcargo}",
                        "{idsetor}",
                        "{setor}",
                        "{listsetor}",
                        "{telefone}",
                        "{dtnasc}",
                        "{docrg}",
                        "{doccpf}",
                        "{docctps}",
                        "{docpispasep}",
                        "{salario}",
                        "{email}",
                        "{senha}",
                        "{telefone}",
                        "{idnivel}",
                        "{nivel}",
                        "{cep}",
                        "{rua}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{id}"
                    ),
                    array(
                        "funcionario",
                        "editar",
                        $object->getPessoaImagem(),
                        $object->getPessoaNome(),
                        $object->getFuncionarioCargo()->getCargoPk(),
                        $object->getFuncionarioCargo()->getCargoNome(),
                        $controllerCargo->exibirFiltrarTodos($cargo),
                        $object->getFuncionarioSetor()->getSetorPK(),
                        $object->getFuncionarioSetor()->getSetorNome(),
                        $controllerSetor->exibirFiltrarTodos($setor),
                        $object->getPessoaTelefone(),
                        $object->getPessoaDtNasc(),
                        $object->getPessoaRG(),
                        $object->getPessoaCPF(),
                        $object->getFuncionarioCTPS(),
                        $object->getFuncionarioPisPasep(),
                        $object->getFuncionarioSalario(),
                        $object->getFuncionarioEmail(),
                        $object->getFuncionarioSenha(),
                        $object->getPessoaTelefone(),
                        $object->getFuncionarioNivelAcesso(),
                        $acesso,
                        $object->getPessoaEndereco()->getCep(),
                        $object->getPessoaEndereco()->getLogradouro(),
                        $object->getPessoaEndereco()->getNumero(),
                        $object->getPessoaEndereco()->getBairro(),
                        $object->getPessoaEndereco()->getCidade(),
                        $object->getPessoaEndereco()->getEstado(),
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
                        "{idcargo}",
                        "{cargo}",
                        "{listcargo}",
                        "{idsetor}",
                        "{setor}",
                        "{listsetor}",
                        "{telefone}",
                        "{dtnasc}",
                        "{docrg}",
                        "{doccpf}",
                        "{docctps}",
                        "{docpispasep}",
                        "{salario}",
                        "{email}",
                        "{senha}",
                        "{telefone}",
                        "{idnivel}",
                        "{nivel}",
                        "{cep}",
                        "{rua}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{id}"
                    ),
                    array(
                        "funcionario",
                        "novo",
                        "",
                        "",
                        "",
                        "",
                        $controllerCargo->exibirFiltrarTodos($cargo),
                        "",
                        "",
                        $controllerSetor->exibirFiltrarTodos($setor),
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
                        "",
                        "",
                        "",
                        "",
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

    public function loadFormProfile(Funcionario $object) {

        $provider = Provider::getProvider();
        $mensagem = new Mensagem();
        $template = file_get_contents("template/templateformprofile.html");
        $setor = new Setor();
        $controllerSetor = new ControllerSetor();
        $cargo = new Cargo();
        $controllerCargo = new ControllerCargo();

        try {

            if (@$_REQUEST['id']) {
                $object->setPessoaPK($_REQUEST['id']);
                $orm = $provider->prepare("SELECT * FROM funcionario AS funci
                    INNER JOIN setor AS st ON funci.funcionario_setor = st.setor_pk
                    INNER JOIN cargo AS cr ON funci.funcionario_cargo = cr.cargo_pk WHERE funcionario_pk = :funcionarioPK");
                $orm->bindValue(":funcionarioPK", $object->getPessoaPK(), Provider::PARAM_INT);
            } else {
                $orm = $provider->prepare("SELECT * FROM funcionario");
            }

            $orm->execute();

            if ($orm->rowCount() == 1):
                $ormListar = $orm->fetch(Provider::FETCH_ASSOC);
                $object->setPessoaPK($ormListar['funcionario_pk']);
                $object->setPessoaDtCriado($ormListar['funcionario_dt_criado']);
                $object->setPessoaImagem($ormListar['funcionario_imagem']);
                $object->setPessoaNome($ormListar['funcionario_nome']);
                $object->setFuncionarioEmail($ormListar['funcionario_email']);
                $object->setFuncionarioSenha($ormListar['funcionario_senha']);
                $object->setPessoaTelefone($ormListar['funcionario_telefone']);
                $object->setPessoaDtNasc($ormListar['funcionario_dtnasc']);
                $object->setPessoaRG($ormListar['funcionario_rg']);
                $object->setPessoaCPF($ormListar['funcionario_cpf']);
                $object->setFuncionarioCTPS($ormListar['funcionario_ctps']);
                $object->setFuncionarioPisPasep($ormListar['funcionario_pispasep']);
                $object->setFuncionarioSalario($ormListar['funcionario_salario']);
                $object->setFuncionarioNivelAcesso($ormListar['funcionario_nivel']);
                $object->setFuncionarioCargo(new Cargo($ormListar['cargo_pk'], $ormListar['cargo_dt_criado'], $ormListar['cargo_nome'], $ormListar['cargo_status']));
                $object->setFuncionarioSetor(new Setor($ormListar['setor_pk'], $ormListar['setor_dt_criado'], $ormListar['setor_nome'], $ormListar['setor_telefone'], $ormListar['setor_status']));
                $object->setPessoaEndereco(new Endereco($ormListar['funcionario_cep'], $ormListar['funcionario_logradouro'], $ormListar['funcionario_numero'], $ormListar['funcionario_bairro'], $ormListar['funcionario_cidade'], $ormListar['funcionario_estado']));

                switch ($object->getFuncionarioNivelAcesso()) {
                    case 0 :
                        $acesso = "DEVOPS";
                        break;
                    case 1 :
                        $acesso = "Diretoria";
                        break;
                    case 2 :
                        $acesso = "Administração";
                        break;
                    case 3 :
                        $acesso = "Recepção";
                        break;
                    case 4 :
                        $acesso = "Funcionários";
                        break;
                    default:
                        $acesso = "";
                        break;
                }
                print (
                str_replace(
                    array(
                        "{view}",
                        "{rota}",
                        "{imagem}",
                        "{nome}",
                        "{idcargo}",
                        "{cargo}",
                        "{listcargo}",
                        "{idsetor}",
                        "{setor}",
                        "{listsetor}",
                        "{telefone}",
                        "{dtnasc}",
                        "{docrg}",
                        "{doccpf}",
                        "{docctps}",
                        "{docpispasep}",
                        "{salario}",
                        "{email}",
                        "{senha}",
                        "{telefone}",
                        "{idnivel}",
                        "{nivel}",
                        "{cep}",
                        "{rua}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{id}"
                    ),
                    array(
                        "profile",
                        "editar",
                        $object->getPessoaImagem(),
                        $object->getPessoaNome(),
                        $object->getFuncionarioCargo()->getCargoPk(),
                        $object->getFuncionarioCargo()->getCargoNome(),
                        $controllerCargo->exibirFiltrarTodos($cargo),
                        $object->getFuncionarioSetor()->getSetorPK(),
                        $object->getFuncionarioSetor()->getSetorNome(),
                        $controllerSetor->exibirFiltrarTodos($setor),
                        $object->getPessoaTelefone(),
                        $object->getPessoaDtNasc(),
                        $object->getPessoaRG(),
                        $object->getPessoaCPF(),
                        $object->getFuncionarioCTPS(),
                        $object->getFuncionarioPisPasep(),
                        $object->getFuncionarioSalario(),
                        $object->getFuncionarioEmail(),
                        $object->getFuncionarioSenha(),
                        $object->getPessoaTelefone(),
                        $object->getFuncionarioNivelAcesso(),
                        $acesso,
                        $object->getPessoaEndereco()->getCep(),
                        $object->getPessoaEndereco()->getLogradouro(),
                        $object->getPessoaEndereco()->getNumero(),
                        $object->getPessoaEndereco()->getBairro(),
                        $object->getPessoaEndereco()->getCidade(),
                        $object->getPessoaEndereco()->getEstado(),
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
                        "{idcargo}",
                        "{cargo}",
                        "{listcargo}",
                        "{idsetor}",
                        "{setor}",
                        "{listsetor}",
                        "{telefone}",
                        "{dtnasc}",
                        "{docrg}",
                        "{doccpf}",
                        "{docctps}",
                        "{docpispasep}",
                        "{salario}",
                        "{email}",
                        "{senha}",
                        "{telefone}",
                        "{idnivel}",
                        "{nivel}",
                        "{cep}",
                        "{logradouro}",
                        "{numero}",
                        "{bairro}",
                        "{cidade}",
                        "{estado}",
                        "{id}"
                    ),
                    array(
                        "profile",
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

    public function validarLogin(Funcionario $object) {

        $provider = Provider::getProvider();
        $historico = new ControllerHistorico();
        $atualizar = new ControllerFuncionario();

        $usuarioEmail = $object->getFuncionarioEmail();
        $usuarioSenha = $object->getFuncionarioSenha();

        $verifica = $provider->prepare("SELECT * FROM funcionario WHERE funcionario_email = :usuarioLogin AND funcionario_senha = :usuarioSenha AND  funcionario_status = 1");
        $verifica->bindValue(":usuarioLogin", $object->getFuncionarioEmail(), Provider::PARAM_STR);
        $verifica->bindValue(":usuarioSenha", $object->getFuncionarioSenha(), Provider::PARAM_STR);
        $verifica->execute();

        if ($verifica->rowCount() == 1):
            $listando = $verifica->fetch(Provider::FETCH_ASSOC);
            $object->setPessoaPK($listando['funcionario_pk']);
            $object->setPessoaDtCriado($listando['funcionario_dt_criado']);
            $object->setPessoaImagem($listando['funcionario_imagem']);
            $object->setPessoaNome($listando['funcionario_nome']);
            $object->setPessoaTelefone($listando['funcionario_telefone']);
            $object->setFuncionarioEmail($listando['funcionario_email']);
            $object->setFuncionarioSenha($listando['funcionario_senha']);
            $object->setFuncionarioNivelAcesso($listando['funcionario_nivel']);
            $object->setPessoaStatusAcesso($listando['funcionario_status_acesso']);

            switch ($object->getFuncionarioNivelAcesso()) {
                case 0:
                    $setor = 'ADM';
                    break;
                case 1:
                    $setor = 'superuser';
                    break;
                case 2:
                    $setor = 'recepcao';
                    break;
                case 3:
                    $setor = 'funcionario';
                    break;
                default:
                    $setor = 'recepcao';
                    break;
            }

            if (($usuarioEmail == $object->getFuncionarioEmail()) && ($usuarioSenha == $object->getFuncionarioSenha())) {

                session_start();

                $_SESSION['id'] = $object->getPessoaPK();
                $_SESSION['usuario'] = $object->getFuncionarioEmail();
                $_SESSION['senha'] = $object->getFuncionarioSenha();
                $_SESSION['nivel'] = $object->getFuncionarioNivelAcesso();
                $_SESSION['foto'] = $object->getPessoaImagem();
                $_SESSION['status'] = $object->getPessoaStatusAcesso();

                if ($object->getPessoaStatusAcesso() == 0) {
                    $object->setPessoaStatusAcesso(1);
                    $atualizar->statusAtivarDesativar($object);
                    $historico->ativarHistoricoFuncionario($object);
                    $_SESSION['idhistorico'] = $provider->lastInsertId();
                    $_SESSION['status'] = 1;
                }

            } else {
                session_destroy();
                unset ($_SESSION['id']);
                unset ($_SESSION['usuario']);
                unset ($_SESSION['senha']);
                unset ($_SESSION['nivel']);
                unset ($_SESSION['foto']);
                unset ($_SESSION['status']);
                unset ($_SESSION['idhistorico']);
            }

        else :
            print "erro";
        endif;
    }

    public function verificaLogin() {
        session_start();
        if (!isset($_SESSION['usuario']) and !isset($_SESSION['senha']) and !isset($_SESSION['nivel']) and ($_SESSION['status'] == 0)) {
            session_destroy();
            unset ($_SESSION['id']);
            unset ($_SESSION['usuario']);
            unset ($_SESSION['senha']);
            unset ($_SESSION['nivel']);
            unset ($_SESSION['foto']);
            unset ($_SESSION['status']);
            unset ($_SESSION['idhistorico']);
            header('location: //localhost/desenvolvimento/php/manager/');
            //header('location: //sysmanfab.com.br/');


        }
    }

    public function validarLogout(Funcionario $funcionario) {
        session_start();
        $historico = new ControllerHistorico();
        $atualizar = new ControllerFuncionario();
        $historicoFunc = new HistoricoFuncionario();

        $id = $_SESSION['id'];
        $status = $_SESSION['status'];
        $nivel = $_SESSION['nivel'];
        $idhist = $_SESSION['idhistorico'];

        $funcionario->setPessoaPK($id);
        $funcionario->setPessoaStatusAcesso($status);
        $funcionario->setFuncionarioNivelAcesso($nivel);

        switch ($nivel) {
            case 0:
                $setor = 'ADM';
                break;
            case 1:
                $setor = 'superuser';
                break;
            case 2:
                $setor = 'recepcao';
                break;
            case 3:
                $setor = 'funcionario';
                break;
            default:
                $setor = 'recepcao';
                break;
        }

        if ($status == 1) {
            $funcionario->setPessoaStatusAcesso(0);
            $atualizar->statusAtivarDesativar($funcionario);
            $historicoFunc->setHistoricoPK($idhist);
            $historico->desativardHistoricoFuncionario($historicoFunc);
        }

        session_destroy();
        unset ($_SESSION['id']);
        unset ($_SESSION['usuario']);
        unset ($_SESSION['senha']);
        unset ($_SESSION['nivel']);
        unset ($_SESSION['foto']);
        unset ($_SESSION['status']);
        unset ($_SESSION['idhistorico']);
        header('location: //localhost/desenvolvimento/php/manager/');
        //header('location: //sysmanfab.com.br/');
    }
}