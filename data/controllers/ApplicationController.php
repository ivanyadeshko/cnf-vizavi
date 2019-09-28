<?php

namespace controllers;

use common\Request;
use common\Tools;
use common\traits\errorsTrait;
use common\WebController;

class ApplicationController
{

    const DEF_CONTROLLER = 'site';

    const DEF_CONTROLLER_METHOD = 'index';


    use errorsTrait;


    /**
     * @return string
     */
    public function run() : string
    {

        if (!$controller = $this->getController()) {
            $this->addError('Controller not found');
            return $this->go404();
        }

        if (!$controller->init()) {
            $this->addError( $controller->getErrors() );
            return $this->go404();
        }

        $methodName = self::getMethodName();
        if (!method_exists($controller, $methodName)) {
            $this->addError('Method not exist');
            return $this->go404();
        }
        $res = $controller->{$methodName}();

        $controller->end();

        return $res;
    }


    /**
     * @return WebController|null
     */
    protected function getController() :? WebController
    {
        $controllerName = self::getControllerName();
        return class_exists($controllerName) ? new $controllerName() : null;
    }


    /**
     * @return string
     */
    protected static function getControllerName() : string
    {
        if ($controllerName =  Request::getInstance()->getController()) {
            $controllerName = Tools::toCamelCase($controllerName);
        }
        $controllerName = $controllerName ? $controllerName : self::DEF_CONTROLLER;
        return 'controllers\\' . Tools::ucfirst($controllerName) . 'Controller';
    }


    /**
     * @return string
     */
    protected static function getMethodName() : string
    {
        if ($methodName = Request::getInstance()->getMethod()) {
            $methodName = Tools::toCamelCase($methodName);
        }
        $methodName = $methodName ? $methodName : self::DEF_CONTROLLER_METHOD;
        return 'Action' . Tools::ucfirst($methodName);
    }


    /**
     * @return string
     */
    private function go404() : string
    {
        if (!$error = implode(', ', $this->getErrors())) {
            $error = 'The request page is not found';
        }

        return $error;
    }


}