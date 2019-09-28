<?php

namespace models;


class Product extends BaseModel
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $id_group;

    /**
     * @var string
     */
    public $name;

    /**
     * @var float
     */
    public $price;


    protected static $relations = [
        'id' => 'id',
        'категория' => 'id_group',
        'наименование' => 'name',
        'цена' => 'price',
    ];

    protected $_all_fields = [];


    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach ($data AS $key => $val) {
            $this->_all_fields[$key] = $val;
        }
    }


    /**
     * @return array
     */
    public function getAllModelFields() : array
    {
        return $this->_all_fields;
    }


}