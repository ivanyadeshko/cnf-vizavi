<?php

namespace models;


use models\file_processing\CsvFileProcessor;

class GroupsComposite extends CsvFileProcessor
{

    /**
     * @var Group[]
     */
    protected $_groups = [];

    /**
     * @var array
     */
    protected $_child_map = [];


    public function addToComposite(array $data): void
    {
        $Group = new Group($data);
        $this->_groups[$Group->id] = $Group;
    }


    /**
     * @return array
     */
    public function getModelRequiredColumns() : array
    {
        return array_keys(Group::getRelations());
    }


    /**
     * @return Group[]
     */
    public function findUpperGroups() : array
    {
        $out = [];
        $min = 999;
        foreach ($this->_groups AS $group) {
            if ($min < $group->getDepthLevel()) {
                continue;
            }
            elseif ($min > $group->getDepthLevel()) {
                $min = $group->getDepthLevel();
                $out = [];
            }
            $out[] = $group;
        }
        return $out;
    }

    /**
     * retuin void
     */
    public function afterLoadFile(): void
    {
        parent::afterLoadFile();
        $this->buildDepthParam();
        $this->buildChildMap();
        $this->buildDescrCascade();
    }


    /**
     * Generate and set addition depth group property
     *
     * @return void
     */
    protected function buildDepthParam() : void
    {
        uasort(
            $this->_groups,
            function (Group $a, Group $b) {
                return $a->id <=> $b->id;
            }
        );

        array_map(function(Group $group) {
            $group->setDepthLevel( $this->calculateDepth($group) );
            return $group;
        }, $this->_groups);
    }


    /**
     * Generate group child map for fast navigation
     *
     * @return void
     */
    protected function buildChildMap() : void
    {
        foreach ($this->_groups AS $group) {
            $key = $group->id_parent;
            if (empty($key)) {
                continue;
            }

            if (!array_key_exists($key, $this->_child_map)) {
                $this->_child_map[$key] = [];
            }
            $this->_child_map[$key][] = $group->id;
        }
    }


    /**
     * Load parent description format if it possible.
     * Unlimited depth
     *
     * @return void
     */
    public function buildDescrCascade() : void
    {
        foreach ($this->_groups AS $group) {
            if (!empty($group->descr_format)) {
                continue;
            }

            $parentGroup = $group;
            while ($parentGroup = $this->findGroupById($parentGroup->id_parent)) {
                if ($parentGroup->inheritance_descr_format != 1) {
                    break;
                }

                if (!empty($parentGroup->descr_format)) {
                    $group->descr_format = $parentGroup->descr_format;
                    break;
                }
            }
        }
    }

    /**
     * @param Group $group
     * @param int $depth
     * @return int
     */
    protected function calculateDepth(Group $group, $depth = 0) : int
    {
        if (!$parentGroup = $this->findGroupById($group->id_parent)) {
            return $depth;
        }
        return $this->calculateDepth($parentGroup, ++$depth);
    }

    /**
     * @param mixed $id
     * @return Group|null
     */
    public function findGroupById($id) : ?Group
    {
        $res = array_key_exists($id, $this->_groups) ? $this->_groups[$id] : null;
        return $res;
    }


    /**
     * @param Group $group
     * @return Group[]
     */
    public function getChildGroups(Group $group) : array
    {
        if (!array_key_exists($group->id, $this->_child_map)) {
            return [];
        }

        $out = [];
        foreach ($this->_child_map[$group->id] AS $id_group) {
            $out[] = $this->findGroupById($id_group);
        }
        return $out;
    }
}