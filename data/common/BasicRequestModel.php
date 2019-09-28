<?php

namespace common;

use common\traits\errorsTrait;

abstract class BasicRequestModel
{

    use errorsTrait;

    /**
     * Load outer data into current model
     *
     * @param mixed $date
     */
    abstract function load($date) : void;

    /**
     * Validate current request
     *
     * @return bool
     */
    abstract function validate() : bool;
}