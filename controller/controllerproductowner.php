<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 15:13
 */

namespace application\controller;


use application\model\dao\productownerDAO;
use application\model\vo\productowner;

class Controllerproductowner {

    public function salvar(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->salvar($object);
    }

    public function atualizar(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->atualizar($object);
    }

    public function remover(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->remover($object);
    }

    public function statusAtivarDesativar(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->statusAtivarDesativar($object);
    }

    public function exibirDetalhar(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->exibirDetalhar($object);
    }

    public function exibirListar(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->exibirListar($object);
    }

    public function exibirFiltrarID(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->exibirFiltrarID($object);
    }

    public function exibirFiltrarNome(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->exibirFiltrarNome($object);
    }

    public function exibirFiltrarTodos(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->exibirFiltrarTodos($object);
    }

    public function contarAtivos() {
        $productowner = new productownerDAO();
        return $productowner->contarAtivos();
    }

    public function loadForm(productowner $object) {
        $productowner = new productownerDAO();
        $productowner->loadForm($object);
    }

}