<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 15:20
 */

namespace application\controller;


use application\model\dao\CargoDAO;
use application\model\vo\Cargo;

class ControllerCargo {

    public function salvar(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->salvar($object);
    }

    public function atualizar(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->atualizar($object);
    }

    public function remover(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->remover($object);
    }

    public function statusAtivarDesativar(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->statusAtivarDesativar($object);
    }

    public function exibirDetalhar(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->exibirDetalhar($object);
    }

    public function exibirListar(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->exibirListar($object);
    }

    public function exibirFiltrarID(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->exibirFiltrarID($object);
    }

    public function exibirFiltrarNome(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->exibirFiltrarNome($object);
    }

    public function exibirFiltrarTodos(Cargo $object) {
        $cargo = new CargoDAO();
        return $cargo->exibirFiltrarTodos($object);
    }

    public function contarAtivos() {
    }

    public function loadForm(Cargo $object) {
        $cargo = new CargoDAO();
        $cargo->loadForm($object);
    }

}