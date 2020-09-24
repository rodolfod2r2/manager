<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 08/03/2018
 * Time: 12:40
 */

namespace application\model\bo;


class SEO {

    public function urlAmigavel($url) {
        $asc = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
        $url = strtr($url, $asc);                             // Remove os acentos
        $url = str_replace(' ', '-', $url);                   // Troca espaços por hifem
        $url = preg_replace('/[^A-Za-z0-9\-]/', '', $url);    // Remove caracteres speciais (menos o hifem)
        $url = preg_replace('/-+/', '-', $url);               // Troca multiplos hifens por um só
        $url = strtolower($url);
        return $url;
    }

    public function gerarHash($quantidade) {
        $caracteres = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
        $quantidadeCaracteres = strlen($caracteres);
        $quantidadeCaracteres--;
        $hash = NULL;
        for ($x = 1; $x <= $quantidade; $x++) {
            $posicao = rand(0, $quantidadeCaracteres);
            $hash .= substr($caracteres, $posicao, 1);
        }
        return $hash;
    }

    public function cryptPass($password, $quantidade){
        $hash = $this->gerarHash($quantidade);
        return $encript = crypt($password, $hash);
    }

    public function loadImages($diretorio) {
        $arquivos = glob($diretorio . "*.{jpg,jpeg,png,bmp}", GLOB_BRACE);
        if ($arquivos == NULL):
        else:
            foreach ($arquivos as $imagens) {
                print ("<div style=\"background-image:url('$imagens')\"></div>");
                $tamanho = count($arquivos);
            }
        endif;
    }

}