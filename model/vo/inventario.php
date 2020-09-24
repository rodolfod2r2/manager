<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 23/03/2018
 * Time: 11:16
 */

namespace application\model\vo;


class Inventario {

    private $inventariopk;
    private $inventarioDtCriado;
    private $inventarioImagem;
    private $inventarioCodigo;
    private $inventarioDescricao;
    private $inventarioMarca;
    private $inventarioModelo;
    private $inventarioNumeroSerie;
    private $inventarioDtAquisicao;
    private $inventarioDtGarantia;
    private $inventarioValor;
    private $inventarioTxDepreciacao;
    private $inventarioSetor;
    private $inventarioResponsavel;
    private $inventarioStatus;
    private $empresaMatriz;
    private $emmpresaFilial;

    /**
     * Inventario constructor.
     * @param $inventariopk
     * @param $inventarioDtCriado
     * @param $inventarioImagem
     * @param $inventarioCodigo
     * @param $inventarioDescricao
     * @param $inventarioMarca
     * @param $inventarioModelo
     * @param $inventarioNumeroSerie
     * @param $inventarioDtAquisicao
     * @param $inventarioDtGarantia
     * @param $inventarioValor
     * @param $inventarioTxDepreciacao
     * @param Setor $inventarioSetor
     * @param Funcionario $inventarioResponsavel
     * @param $inventarioStatus
     * @param $empresaMatriz
     * @param $emmpresaFilial
     */
    public function __construct($inventariopk = null, $inventarioDtCriado = null, $inventarioImagem = null, $inventarioCodigo = null, $inventarioDescricao = null, $inventarioMarca = null, $inventarioModelo = null, $inventarioNumeroSerie = null, $inventarioDtAquisicao = null, $inventarioDtGarantia = null, $inventarioValor = null, $inventarioTxDepreciacao = null, Setor $inventarioSetor = null, Funcionario $inventarioResponsavel = null, $inventarioStatus = null, $empresaMatriz = null, $emmpresaFilial = null) {
        $this->inventariopk = $inventariopk;
        $this->inventarioDtCriado = $inventarioDtCriado;
        $this->inventarioImagem = $inventarioImagem;
        $this->inventarioCodigo = $inventarioCodigo;
        $this->inventarioDescricao = $inventarioDescricao;
        $this->inventarioMarca = $inventarioMarca;
        $this->inventarioModelo = $inventarioModelo;
        $this->inventarioNumeroSerie = $inventarioNumeroSerie;
        $this->inventarioDtAquisicao = $inventarioDtAquisicao;
        $this->inventarioDtGarantia = $inventarioDtGarantia;
        $this->inventarioValor = $inventarioValor;
        $this->inventarioTxDepreciacao = $inventarioTxDepreciacao;
        $this->inventarioSetor = $inventarioSetor;
        $this->inventarioResponsavel = $inventarioResponsavel;
        $this->inventarioStatus = $inventarioStatus;
        $this->empresaMatriz = $empresaMatriz;
        $this->emmpresaFilial = $emmpresaFilial;
    }

    /**
     * @return mixed
     */
    public function getInventariopk() {
        return $this->inventariopk;
    }

    /**
     * @return mixed
     */
    public function getInventarioDtCriado() {
        return $this->inventarioDtCriado;
    }

    /**
     * @return mixed
     */
    public function getInventarioImagem() {
        return $this->inventarioImagem;
    }

    /**
     * @return mixed
     */
    public function getInventarioCodigo() {
        return $this->inventarioCodigo;
    }

    /**
     * @return mixed
     */
    public function getInventarioDescricao() {
        return $this->inventarioDescricao;
    }

    /**
     * @return mixed
     */
    public function getInventarioMarca() {
        return $this->inventarioMarca;
    }

    /**
     * @return mixed
     */
    public function getInventarioModelo() {
        return $this->inventarioModelo;
    }

    /**
     * @return mixed
     */
    public function getInventarioNumeroSerie() {
        return $this->inventarioNumeroSerie;
    }

    /**
     * @return mixed
     */
    public function getInventarioDtAquisicao() {
        return $this->inventarioDtAquisicao;
    }

    /**
     * @return mixed
     */
    public function getInventarioDtGarantia() {
        return $this->inventarioDtGarantia;
    }

    /**
     * @return mixed
     */
    public function getInventarioValor() {
        return $this->inventarioValor;
    }

    /**
     * @return mixed
     */
    public function getInventarioTxDepreciacao() {
        return $this->inventarioTxDepreciacao;
    }

    /**
     * @return Setor
     */
    public function getInventarioSetor() {
        return $this->inventarioSetor;
    }

    /**
     * @return Funcionario
     */
    public function getInventarioResponsavel() {
        return $this->inventarioResponsavel;
    }

    /**
     * @return mixed
     */
    public function getInventarioStatus() {
        return $this->inventarioStatus;
    }

    /**
     * @return mixed
     */
    public function getEmpresaMatriz() {
        return $this->empresaMatriz;
    }

    /**
     * @return mixed
     */
    public function getEmmpresaFilial() {
        return $this->emmpresaFilial;
    }

    /**
     * @param mixed $inventariopk
     */
    public function setInventariopk($inventariopk) {
        $this->inventariopk = $inventariopk;
    }

    /**
     * @param mixed $inventarioDtCriado
     */
    public function setInventarioDtCriado($inventarioDtCriado) {
        $this->inventarioDtCriado = $inventarioDtCriado;
    }

    /**
     * @param mixed $inventarioImagem
     */
    public function setInventarioImagem($inventarioImagem) {
        $this->inventarioImagem = $inventarioImagem;
    }

    /**
     * @param mixed $inventarioCodigo
     */
    public function setInventarioCodigo($inventarioCodigo) {
        $this->inventarioCodigo = $inventarioCodigo;
    }

    /**
     * @param mixed $inventarioDescricao
     */
    public function setInventarioDescricao($inventarioDescricao) {
        $this->inventarioDescricao = $inventarioDescricao;
    }

    /**
     * @param mixed $inventarioMarca
     */
    public function setInventarioMarca($inventarioMarca) {
        $this->inventarioMarca = $inventarioMarca;
    }

    /**
     * @param mixed $inventarioModelo
     */
    public function setInventarioModelo($inventarioModelo) {
        $this->inventarioModelo = $inventarioModelo;
    }

    /**
     * @param mixed $inventarioNumeroSerie
     */
    public function setInventarioNumeroSerie($inventarioNumeroSerie) {
        $this->inventarioNumeroSerie = $inventarioNumeroSerie;
    }

    /**
     * @param mixed $inventarioDtAquisicao
     */
    public function setInventarioDtAquisicao($inventarioDtAquisicao) {
        $this->inventarioDtAquisicao = $inventarioDtAquisicao;
    }

    /**
     * @param mixed $inventarioDtGarantia
     */
    public function setInventarioDtGarantia($inventarioDtGarantia) {
        $this->inventarioDtGarantia = $inventarioDtGarantia;
    }

    /**
     * @param mixed $inventarioValor
     */
    public function setInventarioValor($inventarioValor) {
        $this->inventarioValor = $inventarioValor;
    }

    /**
     * @param mixed $inventarioTxDepreciacao
     */
    public function setInventarioTxDepreciacao($inventarioTxDepreciacao) {
        $this->inventarioTxDepreciacao = $inventarioTxDepreciacao;
    }

    /**
     * @param Setor $inventarioSetor
     */
    public function setInventarioSetor(Setor $inventarioSetor) {
        $this->inventarioSetor = $inventarioSetor;
    }

    /**
     * @param Funcionario $inventarioResponsavel
     */
    public function setInventarioResponsavel(Funcionario $inventarioResponsavel) {
        $this->inventarioResponsavel = $inventarioResponsavel;
    }

    /**
     * @param mixed $inventarioStatus
     */
    public function setInventarioStatus($inventarioStatus) {
        $this->inventarioStatus = $inventarioStatus;
    }

    /**
     * @param mixed $empresaMatriz
     */
    public function setEmpresaMatriz($empresaMatriz) {
        $this->empresaMatriz = $empresaMatriz;
    }

    /**
     * @param mixed $emmpresaFilial
     */
    public function setEmmpresaFilial($emmpresaFilial) {
        $this->emmpresaFilial = $emmpresaFilial;
    }
    
    


}