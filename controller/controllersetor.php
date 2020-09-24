<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 15:18
 */

namespace application\controller;


use application\model\dao\SetorDAO;
use application\model\vo\Setor;

class ControllerSetor {

    public function salvar(Setor $object) {
        $setor = new SetorDAO();
        $setor->salvar($object);
    }

    public function atualizar(Setor $object) {
        $setor = new SetorDAO();
        $setor->atualizar($object);
    }

    public function remover(Setor $object) {
        $setor = new SetorDAO();
        $setor->remover($object);
    }

    public function statusAtivarDesativar(Setor $object) {
        $setor = new SetorDAO();
        $setor->statusAtivarDesativar($object);
    }

    public function exibirDetalhar(Setor $object) {
        $setor = new SetorDAO();
        $setor->exibirDetalhar($object);
    }

    public function exibirListar(Setor $object) {
        $setor = new SetorDAO();
        $setor->exibirListar($object);
    }

    public function exibirFiltrarID(Setor $object) {
        $setor = new SetorDAO();
        return $setor->exibirFiltrarID($object);
    }

    public function exibirFiltrarNome(Setor $object) {
        $setor = new SetorDAO();
        return $setor->exibirFiltrarNome($object);
    }

    public function exibirFiltrarTodos(Setor $object) {
        $setor = new SetorDAO();
        return $setor->exibirFiltrarTodos($object);
    }

    public function contarAtivos() {
    }

    public function loadForm(Setor $object) {
        $setor = new SetorDAO();
        $setor->loadForm($object);
    }

}