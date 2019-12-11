<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore\App\Traits;

trait Input {
    /**
     * Ex: Input::get() OR Input::get('key') OR Input::get('key', 'default_value')
     */

    public static function __callStatic($name, $arguments)
    {
        switch(strtoupper($name)) {
            case 'GET':
                    $superglobal = $_GET;
                break;
            case 'POST':
                    $superglobal = $_POST;
                break;
            case 'GET_POST':
                    $superglobal = $_GET + $_POST;
                break;
            case 'COOKIE':
                    $superglobal = $_COOKIE;
                break;
            case 'SERVER':
                    $superglobal = $_SERVER;
                break;
            default:
                throw new Exception('Input::' . $name . ' is not a valid method');
        }

        $superglobal = cleanValue($superglobal);

        if(!isset($arguments[0])) {
            return $superglobal;
        }

        $default_value = isset($arguments[1]) ? $arguments[1] : null;

        //in case name is a multi key
        if(strstr($arguments[0], '.')) {
            return arrayMultiKey($superglobal, $arguments[0], $default_value);
        }

        return isset($superglobal[$arguments[0]]) ? $superglobal[$arguments[0]] : $default_value;
    }
}
