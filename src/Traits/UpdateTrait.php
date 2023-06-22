<?php

namespace ErnandesRS\EasyCrud\Traits;

trait UpdateTrait
{
    /**
     * Update
     *
     * @param array $data
     * @return \ErnandesRS\EasyCrud\EasyCrud
     */
    public function update(array $data)
    {
        parent::update($data);
        return $this;
    }
}