<?php

namespace models;


class Group extends BaseModel
{

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $id_parent;

    /**
     * @var string
     */
    public $descr_format;

    /**
     * @var bool
     */
    public $inheritance_descr_format;

    protected $_depth_level = 0;

    protected static $relations = [
        'id' => 'id',
        'наименование' => 'name',
        'родитель' => 'id_parent',
        'формат описания товаров' => 'descr_format',
        'наследовать дочерним' => 'inheritance_descr_format',
    ];


    /**
     * @param int $level
     * @return void
     */
    public function setDepthLevel(int $level) : void
    {
        $this->_depth_level = $level;
    }


    /**
     * @return int
     */
    public function getDepthLevel() : int
    {
        return $this->_depth_level;
    }


    /**
     * @param Product $product
     * @return string
     */
    public function loadProductDescription(Product $product) : string
    {
        $model_fields = $product->getAllModelFields();

        $this->descr_format = preg_replace_callback(
            '/%([^%]+)%/',
            function (array $matches) use ($model_fields) {
                if (array_key_exists($matches[1], $model_fields)) {
                    return $model_fields[$matches[1]];
                }
                return 'UNDEFINED';
            },
            $this->descr_format
        );

        return $this->descr_format;
    }

}