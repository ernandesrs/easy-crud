<?php

namespace ErnandesRS\EasyCrud\Traits;

trait InsertTrait
{
    /**
     * Data
     *
     * @param array $data
     * @return \ErnandesRS\EasyCrud\EasyCrud
     */
    public function create(array $data)
    {
        parent::insert($data);
        return $this;
    }
}