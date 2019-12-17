<?php
/*
 * Created on Tue Dec 12 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore\App\General;

use Illuminate\Support\Facades\Request;

class OcCore
{
    /**
     * You can use Startup::getRegistry() to access to OpenCart registry
     * Example: Startup::getRegistry('request')->get
     */

    public function getTokenStr()
    {
        if (isOc3()) {
            $token_str = 'user_token=' . Request::input('user_token');
        } else {
            $token_str = 'token=' . Request::input('token');
        }

        return $token_str;
    }
}
