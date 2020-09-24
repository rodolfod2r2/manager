<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 31/01/2018
 * Time: 12:55
 */

namespace application;

function load($namespace) {
    $splitpath = explode('\\', $namespace);
    $path = '../';
    $name = '';
    $firstword = true;
    for ($i = 0; $i < count($splitpath); $i++) {
        if ($splitpath[$i] && !$firstword) {
            if ($i == count($splitpath) - 1)
                $name = $splitpath[$i];
            else
                $path .= DIRECTORY_SEPARATOR . $splitpath[$i];
        }
        if ($splitpath[$i] && $firstword) {
            if ($splitpath[$i] != __NAMESPACE__)
                break;
            $firstword = false;
        }
    }
    if (!$firstword) {
        $fullpath = $path . DIRECTORY_SEPARATOR . $name . '.php';
        return require_once(strtolower($fullpath));
    }
    return false;
}

/**
 * @param $absPath
 * @return mixed
 */
function loadPath($absPath) {
    return require_once(strtolower($absPath));
}

spl_autoload_register(__NAMESPACE__ . '\load');