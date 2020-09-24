<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 02/03/2018
 * Time: 11:34
 */

namespace application\model\bo;


class Mensagem {

    const SUCESSO = "SUCESSO AO SALVAR";
    const ERRO_EXISTE = "REGISTRO JÁ EXISTE";
    const ERRO_NEXISTE = "REGISTRO NÃO EXISTE";

    const SUCESSO_SALVAR = "SUCESSO AO SALVAR";
    const SUCESSO_ATUALIZAR = "SUCESSO AO ATUALIZAR";
    const SUCESSO_REMOVER = "SUCESSO AO REMOVER";

    const AVISO = "AVISO";
    const AVISO_EXIBIR = "REGISTRO NÃO EXISTE";
    const AVISO_SALVAR = "REGISTRO JÁ EXISTE";
    const AVISO_ATUALIZAR = "AVISO AO ATUALIZAR";
    const AVISO_REMOVER = "REGISTRO NÃO EXISTE";

    const ERRO_SALVAR = "ERRO AO SALVAR";
    const ERRO_ATUALIZAR = "ERRO AO ATUALIZAR";
    const ERRO_REMOVER = "ERRO AO REMOVER";

    const SEM_REGISTRO = "";


    private $response;

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $this->response = $response;
    }

}