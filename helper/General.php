<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

use Illuminate\Support\HtmlString;

function isCompatible()
{
    return (isOc23() || $this->isOc3());
}

function isOc23()
{
    return version_compare(VERSION, '2.3.0.0', '>=');
}

function isOc3()
{
    return version_compare(VERSION, '3.0.0.0') >= 0;
}

function isAwebDropshipping()
{
    return defined('AWEB_VERSION');
}

function multikey(&$arr, $path, $value, $separator = '.')
{
    $keys = explode($separator, $path);

    foreach ($keys as $key) {
        $arr = &$arr[$key];
    }

    $arr = $value;
}

function arrayMultiKey($superglobal, $slices, $default_value)
{
    $slices = is_string($slices) ? explode('.', $slices) : $slices;
    if (!is_string($superglobal) && $slices) {
        $var_name = array_shift($slices);
        if (isset($superglobal[$var_name])) {
            return arrayMultiKey($superglobal[$var_name], $slices, $default_value);
        } else {
            return $default_value;
        }
    } else {
        return $superglobal ? $superglobal : $default_value;
    }
}

function cleanValue($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            unset($data[$key]);

            $data[cleanValue($key)] = cleanValue($value);
        }
    } else {
        $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
    }

    return $data;
}

if (!function_exists('pre')) {
    function pre($var, $exit = false)
    {
        echo "<pre>" . print_r($var, true) . "</pre>\n";
        if (!empty($exit)) exit();
    }
}

if (!function_exists('getToken')) {
    function getToken()
    {
        $request = app('request');

        return isOc3() ? $request->get('user_token') : $request->get('token');
    }
}

if (!function_exists('getTokenKey')) {
    function getTokenKey()
    {
        return (isOc3() ? 'user_token' : 'token');
    }
}

if (!function_exists('token_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function token_field()
    {
        return new HtmlString('<input type="hidden" name="' . getTokenKey() . '" value="' . getToken() . '">');
    }
}
