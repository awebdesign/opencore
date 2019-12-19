<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

namespace OpenCore\App\Traits;

trait Errors {

    private $errors = [];

    public function addError($error)
    {
        $this->addErrors(array($error));
    }

    public function addErrors($errors)
    {
        $errors = !is_array($errors) ? array($errors) : $errors;
        $this->errors = array_merge($this->errors, $errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasError($name)
    {
        return isset($this->errors[$name]);
    }

    public function getError($name)
    {
        return isset($this->errors[$name]) ? $this->errors[$name] : '';
    }

    public function error($name)
    {
        echo $this->getError($name);
    }
}
