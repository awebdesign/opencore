<?php
/*
 * Created on Tue Dec 12 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\App\General;

use Illuminate\Support\Facades\Request;

class OcCore
{
    /**
     * You can use Startup::getRegistry() to access to OpenCart registry
     * Example: Startup::getRegistry('request')->get
     */

    public function getTokenStr()
    {
        return $this->getTokenKey() . '=' . $this->getToken();
    }

    public function getToken()
    {
        return isOc3() ? Request::input('user_token') : Request::input('token');
    }

    public function getTokenKey()
    {
        return (isOc3() ? 'user_token' : 'token');
    }
}
