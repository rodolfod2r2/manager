<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:58
 */

namespace application\model\vo;


class Historicoproductowner extends Historico {

    private $historicoSetor;
    private $historicoResponsavel;
    private $historicoInfo;
    private $historicoproductownerFK;

    public function __construct($historicoPK = null, $historicoDtEntrada = null, $historicoDtSaida = null, $historicoStatusAcesso = null, $historicoSetor = null, $historicoResponsavel = null, $historicoInfo = null, $historicoproductownerFK = null) {
        parent::__construct($historicoPK, $historicoDtEntrada, $historicoDtSaida, $historicoStatusAcesso);
        $this->historicoSetor = $historicoSetor;
        $this->historicoResponsavel = $historicoResponsavel;
        $this->historicoInfo = $historicoInfo;
        $this->historicoproductownerFK = $historicoproductownerFK;
    }

    public function getHistoricoSetor() {
        return $this->historicoSetor;
    }

    public function setHistoricoSetor($historicoSetor) {
        $this->historicoSetor = $historicoSetor;
    }

    public function getHistoricoResponsavel() {
        return $this->historicoResponsavel;
    }

    public function setHistoricoResponsavel($historicoResponsavel) {
        $this->historicoResponsavel = $historicoResponsavel;
    }

    public function getHistoricoInfo() {
        return $this->historicoInfo;
    }

    public function setHistoricoInfo($historicoInfo) {
        $this->historicoInfo = $historicoInfo;
    }

    public function getHistoricoproductownerFK() {
        return $this->historicoproductownerFK;
    }

    public function setHistoricoproductownerFK($historicoproductownerFK) {
        $this->historicoproductownerFK = $historicoproductownerFK;
    }
}