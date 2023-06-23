<?php

namespace ErnandesRS\EasyCrud\Traits;

trait SelectTrait
{
    /**
     * Limit
     *
     * @param integer $limit
     * @return \ErnandesRS\EasyCrud\EasyCrud
     */
    public function limit(int $limit = 25)
    {
        parent::limit($limit);
        return $this;
    }

    /**
     * Find By Primary key
     *
     * @param integer $id
     * @param string $fields
     * @return null|\ErnandesRS\EasyCrud\EasyCrud
     */
    public function find(int $id, string $fields = "*")
    {
        return $this->where($this->primaryKey, "=", $id)->getOne($fields);
    }

    /**
     * Get one
     *
     * @param string $fields
     * @return null|\ErnandesRS\EasyCrud\EasyCrud
     */
    public function getOne(string $fields = "*")
    {
        parent::select($fields)->fetch();

        return count($this->data) ? current($this->data) : null;
    }

    /**
     * Get all
     *
     * @param string $fields
     * @return array
     */
    public function getAll(string $fields = "*")
    {
        parent::select($fields)->fetch();
        return $this->data;
    }
}