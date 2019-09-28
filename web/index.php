<?php

include_once (dirname(__DIR__) . DIRECTORY_SEPARATOR . 'requirements.php');

echo (new \controllers\ApplicationController())->run();