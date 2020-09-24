<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:31
 */

namespace application\model\vo;


class Setor {

    private $setorPK;
    private $setorDtCriado;
    private $setorNome;
    private $setorTelefone;
    private $setorStatus;

    public function __construct($setorPK = null, $setorDtCriado = null, $setorNome = null, $setorTelefone = null, $setorStatus = null) {
        $this->setorPK = $setorPK;
        $this->setorDtCriado = $setorDtCriado;
        $this->setorNome = $setorNome;
        $this->setorTelefone = $setorTelefone;
        $this->setorStatus = $setorStatus;
    }

    public function getSetorPK() {
        return $this->setorPK;
    }

    public function setSetorPK($setorPK) {
        $this->setorPK = $setorPK;
    }

    public function getSetorDtCriado() {
        return $this->setorDtCriado;
    }

    public function setSetorDtCriado($setorDtCriado) {
        $this->setorDtCriado = $setorDtCriado;
    }

    public function getSetorNome() {
        return $this->setorNome;
    }

    public function setSetorNome($setorNome) {
        $this->setorNome = $setorNome;
    }

    public function getSetorTelefone() {
        return $this->setorTelefone;
    }

    public function setSetorTelefone($setorTelefone) {
        $this->setorTelefone = $setorTelefone;
    }

    public function getSetorStatus() {
        return $this->setorStatus;
    }

    public function setSetorStatus($setorStatus) {
        $this->setorStatus = $setorStatus;
    }
}