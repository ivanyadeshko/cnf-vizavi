<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('_ROOT_DIR_', __DIR__ );
define('_WEB_DIR_', _ROOT_DIR_ . DIRECTORY_SEPARATOR . 'web');
define('_DATA_DIR_', _ROOT_DIR_ . DIRECTORY_SEPARATOR . 'data');

require_once(_DATA_DIR_ . "/core/class_loader.php");