<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace AwebCore\App\System\Traits;

use Exception;

trait OpenCart
{
    /*public $registry;

    public function setInstance($registry)
    {
        $this->registry = $registry;
        return $this->registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public static function getInstance()
    {
        if ($this->registry === NULL) {
            throw new Exception('Oc Registry Instance Could not be loaded');
        }
        return $this->registry;
    }*/

    public function getOcModel($name)
    {
        $instance_name = 'model_' . str_replace('/', '_', $name);
        if (!$this->registry->has($instance_name)) {
            $this->load->model($name);
        }

        return $this->registry->get($instance_name);
    }
}
