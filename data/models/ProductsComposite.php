<?php

namespace models;


use models\file_processing\CsvFileProcessor;

class ProductsComposite extends CsvFileProcessor
{

    /**
     * @var Product[]
     */
    protected $_products = [];


    public function addToComposite(array $data): void
    {
        $this->_products[] = new Product($data);
    }


    /**
     * @return array
     */
    public function getModelRequiredColumns() : array
    {
        return array_keys(Product::getRelations());
    }


    /**
     * @param mixed $id_group
     * @return Product[]
     */
    public function findForGroup($id_group) : array
    {
        $out = [];
        foreach ($this->_products AS $product) {
            if ($product->id_group == $id_group) {
                $out[] = $product;
            }
        }
        return $out;
    }
}