<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 23/03/2018
 * Time: 11:14
 */

namespace application\controller;


use application\model\dao\InventarioDAO;
use application\model\vo\Inventario;

class ControllerInventario {

    public function salvar(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->salvar($object);
    }

    public function atualizar(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->atualizar($object);
    }

    public function remover(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->remover($object);
    }

    public function statusAtivarDesativar(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->statusAtivarDesativar($object);
    }

    public function exibirDetalhar(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->exibirDetalhar($object);
    }

    public function exibirListar(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->exibirListar($object);
    }

    public function exibirFiltrarID(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->exibirFiltrarID($object);
    }

    public function exibirFiltrarNome(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->exibirFiltrarNome($object);
    }

    public function exibirFiltrarTodos(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->exibirFiltrarTodos($object);
    }

    public function contarAtivos() {
        $inventario = new InventarioDAO();
        $inventario->contarAtivos();
    }

    public function loadForm(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->loadForm($object);
    }

    public function InventarioTotal(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->InventarioTotal($object);
    }

    public function InventarioTotalSetor(Inventario $object) {
        $inventario = new InventarioDAO();
        $inventario->InventarioTotalSetor($object);
    }

}