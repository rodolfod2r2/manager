<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 15:21
 */

namespace application\controller;


use application\model\dao\FuncionarioDAO;
use application\model\vo\Funcionario;

class ControllerFuncionario {

    public function salvar(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->salvar($object);
    }

    public function atualizar(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->atualizar($object);
    }

    public function remover(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->remover($object);
    }

    public function statusAtivarDesativar(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->statusAtivarDesativar($object);
    }

    public function exibirDetalhar(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->exibirDetalhar($object);
    }

    public function exibirListar(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->exibirListar($object);
    }

    public function exibirFiltrarID(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        return $funcionario->exibirFiltrarID($object);
    }

    public function exibirFiltrarNome(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        return $funcionario->exibirFiltrarNome($object);
    }

    public function exibirFiltrarTodos(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        return $funcionario->exibirFiltrarTodos($object);
    }

    public function contarAtivos() {
    }

    public function loadForm(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->loadForm($object);
    }

    public function loadFormProfile(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->loadFormProfile($object);
    }

    public function validarLogin(Funcionario $object) {
        $funcionario = new FuncionarioDAO();
        $funcionario->validarLogin($object);
    }
    public function verificaLogin() {
        $funcionario = new FuncionarioDAO();
        $funcionario->verificaLogin();
    }

    public function validarLogout(Funcionario $object){
        $funcionario = new FuncionarioDAO();
        $funcionario->validarLogout($object);
    }

}