<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 12/03/2018
 * Time: 08:44
 */

namespace application\controller;


use application\model\dao\HistoricoDAO;
use application\model\vo\Funcionario;
use application\model\vo\HistoricoFuncionario;
use application\model\vo\Setor;
use application\model\vo\productowner;

class ControllerHistorico {

    public function ativarHistoricoFuncionario(Funcionario $object) {
        $historico = new HistoricoDAO();
        $historico->ativarHistoricoFuncionario($object);
    }

    public function desativardHistoricoFuncionario(HistoricoFuncionario $object) {
        $historico = new HistoricoDAO();
        $historico->desativardHistoricoFuncionario($object);
    }

    public function exibirListarPonto() {
        $historico = new HistoricoDAO();
        $historico->exibirListarPonto();
    }

    public function exibirListarproductowner() {
        $historico = new HistoricoDAO();
        $historico->exibirListarproductowner();
    }

    public function ativarHistoricoproductowner(productowner $productowner, Funcionario $funcionario, Setor $setor) {
        $historico = new HistoricoDAO();
        $historico->ativarHistoricoproductowner($productowner, $funcionario, $setor);
    }

    public function desativardHistoricoproductowner($historicoID) {
        $historico = new HistoricoDAO();
        $historico->desativardHistoricoproductowner($historicoID);
    }

    public function desativarHistoricoproductownerFuncionario() {
        $historico = new HistoricoDAO();
        $historico->desativarHistoricoproductownerFuncionario();
    }

}