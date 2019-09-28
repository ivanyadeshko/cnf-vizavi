<?php

namespace common;


class Request
{

    protected static $_instance;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    /**
     * @param string $name
     * @param null|mixed $def
     * @return mixed
     */
    public function get($name, $def = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $def;
    }


    /**
     * @param string $name
     * @param null|mixed $def
     * @return mixed
     */
    public function post($name, $def = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $def;
    }


    /**
     * @return array
     */
    public function files() : array
    {
        return is_array($_FILES) ? $_FILES : [];
    }

    /**
     * @return string
     */
    public function getController() : ?string
    {
        $res = explode('/', $this->getUri());
        return !empty($res[0]) ? $res[0] : null;
    }

    /**
     * @return string
     */
    public function getMethod() : ?string
    {
        $res = explode('/', $this->getUri());
        return !empty($res[1]) ? $res[1] : null;
    }

    /**
     * @return string
     */
    public function getUri() : string
    {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    public static function getInstance() : self
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}