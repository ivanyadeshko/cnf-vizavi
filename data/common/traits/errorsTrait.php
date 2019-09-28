<?php

namespace common\traits;


trait errorsTrait
{
    protected $errors = [];


    public function addErrors(array $errors) : void
    {
        $this->errors = array_merge($this->errors, $errors);
    }


    public function addError($error, $key = null) : void
    {
        $this->errors[] = $error;
    }


    public function getErrors() : array
    {
        return $this->errors;
    }

}