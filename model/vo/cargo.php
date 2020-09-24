<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 10:32
 */

namespace application\model\vo;


class Cargo {

    private $cargoPk;
    private $cargoDtCriado;
    private $cargoNome;
    private $cargoStatus;

    public function __construct($cargoPk = null, $cargoDtCriado = null, $cargoNome = null, $cargoStatus = null) {
        $this->cargoPk = $cargoPk;
        $this->cargoDtCriado = $cargoDtCriado;
        $this->cargoNome = $cargoNome;
        $this->cargoStatus = $cargoStatus;
    }


    public function getCargoPk() {
        return $this->cargoPk;
    }

    public function setCargoPk($cargoPk) {
        $this->cargoPk = $cargoPk;
    }

    public function getCargoDtCriado() {
        return $this->cargoDtCriado;
    }

    public function setCargoDtCriado($cargoDtCriado) {
        $this->cargoDtCriado = $cargoDtCriado;
    }

    public function getCargoNome() {
        return $this->cargoNome;
    }

    public function setCargoNome($cargoNome) {
        $this->cargoNome = $cargoNome;
    }

    public function getCargoStatus() {
        return $this->cargoStatus;
    }

    public function setCargoStatus($cargoStatus) {
        $this->cargoStatus = $cargoStatus;
    }
}