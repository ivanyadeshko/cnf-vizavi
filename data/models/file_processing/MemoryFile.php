<?php

namespace models\file_processing;


class MemoryFile
{

    /**
     * @var string
     */
    public $name;


    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $tmp_name;

    /**
     * @var int error code
     */
    public $error;

    /**
     * @var int file size in bytes
     */
    public $size;


    private $_handle;

    public function __construct(array $file)
    {
        foreach ($file AS $k => $v) {
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }


    public function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }


    /**
     * @return false|string
     */
    public function readNextRow() :? string
    {
        if (!$handle = $this->getHandle()) {
            return null;
        }
        return ($string = fgets($handle, 4096)) ? $string : null;
    }


    /**
     * @param string $flag
     * @param bool $re_open
     * @return bool|resource
     */
    public function getHandle($re_open = false, $flag = "r")
    {
        if (!$this->_handle || ($re_open && $this->closeHandle())) {
            $this->_handle = is_readable($this->tmp_name) ? fopen($this->tmp_name, $flag) : null;
        }
        return $this->_handle;
    }


    /**
     * @return bool
     */
    public function rewind() : bool
    {
        return rewind($this->getHandle());
    }

    /**
     * @return bool
     */
    private function closeHandle() : bool
    {
        if ($this->_handle) {
            fclose($this->_handle);
            $this->_handle = null;
        }
        return true;
    }


    public function __destruct()
    {
        $this->closeHandle();
    }

}