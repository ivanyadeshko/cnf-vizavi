<?php

namespace models;


abstract class BaseModel
{

    /**
     * Current model required fields
     *
     * @var array
     */
    protected static $relations = [];


    /**
     * Addition fields, imported from csv file
     *
     * @var array
     */
    protected $custom_fields = [];



    public function __construct(array $data)
    {
        foreach ($data AS $col => $val) {
            if (array_key_exists($col, static::$relations)) {
                $prop = static::$relations[$col];
                $this->{$prop} = $val;
            }
            else {
                $this->custom_fields[$col] = $val;
            }
        }
    }

    /**
     * @return array
     */
    protected function getBackRelations() : array
    {
        return array_flip(self::getRelations());
    }

    /**
     * @return array
     */
    public static function getRelations() : array
    {
        return self::$relations;
    }

}