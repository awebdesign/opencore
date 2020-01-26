<?php
/*
 * Created on Wed Jan 22 2020 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\Support;

class OcLoader
{
    private $loaded = null;
    private $flash = null;

    function flash($name, $val = true)
    {
        $this->flash[$name] = true;

        $this->set($name, $val);
    }

    function set($name, $val = true)
    {
        $this->unsetFlash($name);

        $this->loaded[$name] = $val;
    }

    function get($name)
    {
        $this->unsetFlash($name);

        return $this->loaded[$name] ?? null;
    }

    private function unsetFlash($name)
    {
        if (isset($this->flash[$name])) {
            unset($this->flash[$name]);
        }
    }
}
