<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 08/03/2018
 * Time: 23:58
 */

namespace application\model\bo;


class Upload {

    public function uploadBaseCode($encoded_data, $name) {
        $binary_data = base64_decode($encoded_data);
        file_put_contents('../resources/image/recepcao/'.$name.'.jpg', $binary_data );
    }

    public function uploadBaseCodeF($encoded_data, $name) {
        $binary_data = base64_decode($encoded_data);
        file_put_contents('../resources/image/funcionario/'.$name.'.jpg', $binary_data );
    }


    public function uploadSimples($Arquivo, $tmpArquivo) {

        $mensagem = new Mensagem();
        if (move_uploaded_file($tmpArquivo, '../resources/image/inventario/' . $Arquivo)) :
            $mensagem->setResponse($mensagem::SUCESSO);
        else :
            $mensagem->setResponse($mensagem::SUCESSO);
        endif;
    }

    public function removeArquivo($url, $arquivo) {
        if (file_exists($url . '/' . $arquivo)) {
            @unlink($url . '/' . $arquivo);
        }
    }

}