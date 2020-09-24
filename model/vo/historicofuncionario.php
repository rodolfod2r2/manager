<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:58
 */

namespace application\model\vo;


class HistoricoFuncionario extends Historico {

    private $HistoricoFuncionarioFK;

    public function __construct($historicoPK = null, $historicoDtEntrada = null, $historicoDtSaida = null, $historicoStatusAcesso = null, $HistoricoFuncionarioFK = null) {
        parent::__construct($historicoPK, $historicoDtEntrada, $historicoDtSaida, $historicoStatusAcesso);
        $this->HistoricoFuncionarioFK = $HistoricoFuncionarioFK;
    }

    public function getHistoricoFuncionarioFK() {
        return $this->HistoricoFuncionarioFK;
    }

    public function setHistoricoFuncionarioFK($HistoricoFuncionarioFK) {
        $this->HistoricoFuncionarioFK = $HistoricoFuncionarioFK;
    }
}