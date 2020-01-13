<?php
/*
 * Created on Fri Jan 10 2020 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Setting extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'SettingRepository'; }

}
