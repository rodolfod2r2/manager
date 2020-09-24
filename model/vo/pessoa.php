<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:03
 */

namespace application\model\vo;


class Pessoa {

    private $pessoaPK;
    private $pessoaDtCriado;
    private $pessoaDtNasc;
    private $pessoaImagem;
    private $pessoaNome;
    private $pessoaRG;
    private $pessoaCPF;
    private $pessoaTelefone;
    private $pessoaEndereco;
    private $pessoaStatus;
    private $pessoaStatusAcesso;

    public function __construct($pessoaPK = null, $pessoaDtCriado = null, $pessoaDtNasc = null, $pessoaImagem = null, $pessoaNome = null, $pessoaRG = null, $pessoaCPF = null, $pessoaTelefone = null, Endereco $pessoaEndereco = null, $pessoaStatus = null, $pessoaStatusAcesso = null) {
        $this->pessoaPK = $pessoaPK;
        $this->pessoaDtCriado = $pessoaDtCriado;
        $this->pessoaDtNasc = $pessoaDtNasc;
        $this->pessoaImagem = $pessoaImagem;
        $this->pessoaNome = $pessoaNome;
        $this->pessoaRG = $pessoaRG;
        $this->pessoaCPF = $pessoaCPF;
        $this->pessoaTelefone = $pessoaTelefone;
        $this->pessoaEndereco = $pessoaEndereco;
        $this->pessoaStatus = $pessoaStatus;
        $this->pessoaStatusAcesso = $pessoaStatusAcesso;
    }

    public function getPessoaPK() {
        return $this->pessoaPK;
    }

    public function setPessoaPK($pessoaPK) {
        $this->pessoaPK = $pessoaPK;
    }

    public function getPessoaDtCriado() {
        return $this->pessoaDtCriado;
    }

    public function setPessoaDtCriado($pessoaDtCriado) {
        $this->pessoaDtCriado = $pessoaDtCriado;
    }

    public function getPessoaDtNasc() {
        return $this->pessoaDtNasc;
    }

    public function setPessoaDtNasc($pessoaDtNasc) {
        $this->pessoaDtNasc = $pessoaDtNasc;
    }

    public function getPessoaImagem() {
        return $this->pessoaImagem;
    }

    public function setPessoaImagem($pessoaImagem) {
        $this->pessoaImagem = $pessoaImagem;
    }

    public function getPessoaNome() {
        return $this->pessoaNome;
    }

    public function setPessoaNome($pessoaNome) {
        $this->pessoaNome = $pessoaNome;
    }

    public function getPessoaRG() {
        return $this->pessoaRG;
    }

    public function setPessoaRG($pessoaRG) {
        $this->pessoaRG = $pessoaRG;
    }

    public function getPessoaCPF() {
        return $this->pessoaCPF;
    }

    public function setPessoaCPF($pessoaCPF) {
        $this->pessoaCPF = $pessoaCPF;
    }

    public function getPessoaTelefone() {
        return $this->pessoaTelefone;
    }

    public function setPessoaTelefone($pessoaTelefone) {
        $this->pessoaTelefone = $pessoaTelefone;
    }

    public function getPessoaEndereco() {
        return $this->pessoaEndereco;
    }

    public function setPessoaEndereco(Endereco $pessoaEndereco) {
        $this->pessoaEndereco = $pessoaEndereco;
    }

    public function getPessoaStatus() {
        return $this->pessoaStatus;
    }

    public function setPessoaStatus($pessoaStatus) {
        $this->pessoaStatus = $pessoaStatus;
    }

    public function getPessoaStatusAcesso() {
        return $this->pessoaStatusAcesso;
    }

    public function setPessoaStatusAcesso($pessoaStatusAcesso) {
        $this->pessoaStatusAcesso = $pessoaStatusAcesso;
    }


}