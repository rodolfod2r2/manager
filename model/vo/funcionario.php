<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:16
 */

namespace application\model\vo;


class Funcionario extends Pessoa {

    private $funcionarioEmail;
    private $funcionarioSenha;
    private $funcionarioDtAdmissao;
    private $funcionarioDtDemissao;
    private $funcionarioCTPS;
    private $funcionarioPisPasep;
    private $funcionarioSetor;
    private $funcionarioCargo;
    private $funcionarioSalario;
    private $funcionarioNivelAcesso;

    public function __construct($pessoaPK = null, $pessoaDtCriado = null, $pessoaDtNasc = null, $pessoaImagem = null, $pessoaNome = null, $pessoaRG = null, $pessoaCPF = null, $pessoaTelefone = null, Endereco $pessoaEndereco = null, $pessoaStatus = null, $pessoaStatusAcesso = null, $funcionarioEmail = null, $funcionarioSenha = null, $funcionarioDtAdmissao = null, $funcionarioDtDemissao = null, $funcionarioCTPS = null, $funcionarioPisPasep = null, Setor $funcionarioSetor = null, Cargo $funcionarioCargo = null, $funcionarioSalario = null, $funcionarioNivelAcesso = null) {
        parent::__construct($pessoaPK, $pessoaDtCriado, $pessoaDtNasc, $pessoaImagem, $pessoaNome, $pessoaRG, $pessoaCPF, $pessoaTelefone, $pessoaEndereco, $pessoaStatus, $pessoaStatusAcesso);
        $this->funcionarioEmail = $funcionarioEmail;
        $this->funcionarioSenha = $funcionarioSenha;
        $this->funcionarioDtAdmissao = $funcionarioDtAdmissao;
        $this->funcionarioDtDemissao = $funcionarioDtDemissao;
        $this->funcionarioCTPS = $funcionarioCTPS;
        $this->funcionarioPisPasep = $funcionarioPisPasep;
        $this->funcionarioSetor = $funcionarioSetor;
        $this->funcionarioCargo = $funcionarioCargo;
        $this->funcionarioSalario = $funcionarioSalario;
        $this->funcionarioNivelAcesso = $funcionarioNivelAcesso;
    }

    public function getFuncionarioEmail() {
        return $this->funcionarioEmail;
    }

    public function setFuncionarioEmail($funcionarioEmail) {
        $this->funcionarioEmail = $funcionarioEmail;
    }

    public function getFuncionarioSenha() {
        return $this->funcionarioSenha;
    }

    public function setFuncionarioSenha($funcionarioSenha) {
        $this->funcionarioSenha = $funcionarioSenha;
    }

    public function getFuncionarioDtAdmissao() {
        return $this->funcionarioDtAdmissao;
    }

    public function setFuncionarioDtAdmissao($funcionarioDtAdmissao) {
        $this->funcionarioDtAdmissao = $funcionarioDtAdmissao;
    }

    public function getFuncionarioDtDemissao() {
        return $this->funcionarioDtDemissao;
    }

    public function setFuncionarioDtDemissao($funcionarioDtDemissao) {
        $this->funcionarioDtDemissao = $funcionarioDtDemissao;
    }

    public function getFuncionarioCTPS() {
        return $this->funcionarioCTPS;
    }

    public function setFuncionarioCTPS($funcionarioCTPS) {
        $this->funcionarioCTPS = $funcionarioCTPS;
    }

    public function getFuncionarioPisPasep() {
        return $this->funcionarioPisPasep;
    }

    public function setFuncionarioPisPasep($funcionarioPisPasep) {
        $this->funcionarioPisPasep = $funcionarioPisPasep;
    }

    public function getFuncionarioSetor() {
        return $this->funcionarioSetor;
    }

    public function setFuncionarioSetor(Setor $funcionarioSetor) {
        $this->funcionarioSetor = $funcionarioSetor;
    }

    public function getFuncionarioCargo() {
        return $this->funcionarioCargo;
    }

    public function setFuncionarioCargo(Cargo $funcionarioCargo) {
        $this->funcionarioCargo = $funcionarioCargo;
    }

    public function getFuncionarioSalario() {
        return $this->funcionarioSalario;
    }

    public function setFuncionarioSalario($funcionarioSalario) {
        $this->funcionarioSalario = $funcionarioSalario;
    }

    public function getFuncionarioNivelAcesso() {
        return $this->funcionarioNivelAcesso;
    }

    public function setFuncionarioNivelAcesso($funcionarioNivelAcesso) {
        $this->funcionarioNivelAcesso = $funcionarioNivelAcesso;
    }

}