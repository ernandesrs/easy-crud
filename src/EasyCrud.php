<?php

namespace ErnandesRS\EasyCrud;

use ErnandesRS\EasyCrud\Core\Crud;
use ErnandesRS\EasyCrud\Traits\DeleteTrait;
use ErnandesRS\EasyCrud\Traits\InsertTrait;
use ErnandesRS\EasyCrud\Traits\SelectTrait;
use ErnandesRS\EasyCrud\Traits\UpdateTrait;

class EasyCrud extends Crud
{
    use SelectTrait;
    use InsertTrait;
    use UpdateTrait;
    use DeleteTrait;

    /**
     * Constructor
     */
    public function __construct(string $table, string $primaryKey = "id")
    {
        parent::__construct($table, $primaryKey);
    }

    /**
     * Where
     *
     * @param string $field
     * @param string $operator
     * @param boolean|float|integer|string $value
     * @return EasyCrud
     */
    public function where(string $field, string $operator, bool|float|int|string $value): EasyCrud
    {
        parent::where($field, $operator, $value);
        return $this;
    }

    /**
     * Or Where
     *
     * @param string $field
     * @param string $operator
     * @param boolean|float|integer|string $value
     * @return EasyCrud
     */
    public function orWhere(string $field, string $operator, bool|float|int|string $value): EasyCrud
    {
        parent::orWhere($field, $operator, $value);
        return $this;
    }

    /**
     * Where Null
     *
     * @param string $field
     * @return EasyCrud
     */
    public function whereNull(string $field)
    {
        parent::whereNull($field);
        return $this;
    }

    /**
     * Where Not Null
     *
     * @param string $field
     * @return EasyCrud
     */
    public function whereNotNull(string $field)
    {
        parent::whereNotNull($field);
        return $this;
    }

    /**
     * Where Null
     *
     * @param string $field
     * @return EasyCrud
     */
    public function orWhereNull(string $field)
    {
        parent::orWhereNull($field);
        return $this;
    }

    /**
     * Where Not Null
     *
     * @param string $field
     * @return EasyCrud
     */
    public function orWhereNotNull(string $field)
    {
        parent::orWhereNotNull($field);
        return $this;
    }

    /**
     * Data
     *
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Set
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get
     *
     * @param string $key
     * @return null|mixed
     */
    public function __get(string $key)
    {
        return $this->data[$key] ?? null;
    }
}