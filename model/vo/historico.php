<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:54
 */

namespace application\model\vo;


class Historico {

    private $historicoPK;
    private $historicoDtEntrada;
    private $historicoDtSaida;
    private $historicoStatusAcesso;

    public function __construct($historicoPK = null, $historicoDtEntrada = null, $historicoDtSaida = null, $historicoStatusAcesso = null) {
        $this->historicoPK = $historicoPK;
        $this->historicoDtEntrada = $historicoDtEntrada;
        $this->historicoDtSaida = $historicoDtSaida;
        $this->historicoStatusAcesso = $historicoStatusAcesso;
    }

    public function getHistoricoPK() {
        return $this->historicoPK;
    }

    public function setHistoricoPK($historicoPK) {
        $this->historicoPK = $historicoPK;
    }

    public function getHistoricoDtEntrada() {
        return $this->historicoDtEntrada;
    }

    public function setHistoricoDtEntrada($historicoDtEntrada) {
        $this->historicoDtEntrada = $historicoDtEntrada;
    }

    public function getHistoricoDtSaida() {
        return $this->historicoDtSaida;
    }

    public function setHistoricoDtSaida($historicoDtSaida) {
        $this->historicoDtSaida = $historicoDtSaida;
    }

    public function getHistoricoStatusAcesso() {
        return $this->historicoStatusAcesso;
    }

    public function setHistoricoStatusAcesso($historicoStatusAcesso) {
        $this->historicoStatusAcesso = $historicoStatusAcesso;
    }

}