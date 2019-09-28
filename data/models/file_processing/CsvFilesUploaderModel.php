<?php

namespace models\file_processing;


use common\BasicRequestModel;
use models\GroupsComposite;
use models\ProductsComposite;

class CsvFilesUploaderModel extends BasicRequestModel
{

    protected $data;


    protected $groups;

    protected $products;

    protected $scenario = [
        'groups' => '\models\GroupsComposite',
        'products' => '\models\ProductsComposite',
    ];


    /**
     * @param mixed $date
     * @return void
     */
    public function load($date): void
    {
        $this->data = $date;
    }


    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!is_array($this->data)) {
            $this->addError('Undefined incoming files');
            return false;
        }

        foreach ($this->scenario AS $name => $class_name) {
            $file = !empty($this->data[$name]) ? $this->data[$name] : null;
            if (!$file || empty($file['tmp_name'])) {
                $this->addError("{$name} file not found!");
                continue;
            }

            $BasicRequestModel = $this->loadClass($name);
            $BasicRequestModel->load($file);
            if (!$BasicRequestModel->validate()) {
                $this->addError(
                    $name . ": " . implode("; ", $BasicRequestModel->getErrors())
                );
            }
        }
        return empty($this->getErrors());
    }


    /**
     * @return GroupsComposite
     */
    public function getGroupsComposite() : GroupsComposite
    {
        return $this->groups ?: new GroupsComposite();
    }


    /**
     * @return ProductsComposite
     */
    public function getProductsComposite() : ProductsComposite
    {
        return $this->products ?: new ProductsComposite();
    }

    /**
     * @param string $name
     * @return BasicRequestModel
     */
    private function loadClass(string $name) : BasicRequestModel
    {
        $class_name = $this->scenario[$name];
        $this->{$name} = new $class_name();
        return $this->{$name};
    }
}