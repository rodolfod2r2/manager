<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:12
 */

namespace application\model\vo;


class productowner extends Pessoa {

    private $productownerTipo;

    public function __construct($pessoaPK = null, $pessoaDtCriado = null, $pessoaDtNasc = null, $pessoaImagem = null, $pessoaNome = null, $pessoaRG = null, $pessoaCPF = null, $pessoaTelefone = null, Endereco $pessoaEndereco = null, $pessoaStatus = null, $pessoaStatusAcesso = null, $productownerTipo = null) {
        parent::__construct($pessoaPK, $pessoaDtCriado, $pessoaDtNasc, $pessoaImagem, $pessoaNome, $pessoaRG, $pessoaCPF, $pessoaTelefone, $pessoaEndereco, $pessoaStatus, $pessoaStatusAcesso);
        $this->productownerTipo = $productownerTipo;
    }

    public function getproductownerTipo() {
        return $this->productownerTipo;
    }

    public function setproductownerTipo($productownerTipo) {
        $this->productownerTipo = $productownerTipo;
    }
}