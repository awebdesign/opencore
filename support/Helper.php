<?php
/*
 * Created on Fri Dec 20 2019 by DaRock
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

if (!function_exists('module_version')) {
    function module_version(\Nwidart\Modules\Laravel\Module $module)
    {
        return $module->version;
    }
}

if (!function_exists('module_asset')) {
    function module_asset($asset)
    {
        $url = Module::asset($asset);

        $url  = substr_replace($url, 'https://', 0, 2);

        return $url;
    }
}
