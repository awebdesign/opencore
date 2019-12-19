<?php
/*
 * Created on Wed Dec 18 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\App\General;

use Illuminate\Routing\UrlGenerator as DefaultUrlGenerator;
use Illuminate\Support\Facades\Request;

class UrlGenerator extends DefaultUrlGenerator
{
    public function route($name, $parameters = [], $absolute = true)
    {
        if(isOc3()) {
            $parameters['user_token'] = Request::input('user_token');
        } else {
            $parameters['token'] = Request::input('token');
        }

        return parent::route($name, $parameters, $absolute);
    }
}
