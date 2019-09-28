<?php

spl_autoload_register(function ($class) {
    $part = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = _DATA_DIR_ . DIRECTORY_SEPARATOR . $part;
    if (is_readable($file)) {
        require_once $file;
    }

});