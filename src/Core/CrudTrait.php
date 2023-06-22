<?php

namespace ErnandesRS\EasyCrud\Core;

trait CrudTrait
{
    /**
     * Where
     *
     * @param string $field
     * @param string $operator
     * @param string|integer|float $value
     * @return \ErnandesRS\EasyCrud\Core\Crud
     */
    protected function where(string $field, string $operator, string|int|float|bool $value)
    {
        $this->conditions[$field][] = ["AND", $field, $operator, $value];
        $this->values[$field][] = [
            $field,
            $value
        ];
        return $this;
    }

    /**
     * Or Where
     *
     * @param string $field
     * @param string $operator
     * @param string|integer|float $value
     * @return \ErnandesRS\EasyCrud\Core\Crud
     */
    protected function orWhere(string $field, string $operator, string|int|float|bool $value)
    {
        $this->conditions[$field][] = ["OR", $field, $operator, $value];
        $this->values[$field][] = [
            $field,
            $value
        ];
        return $this;
    }

    /**
     * Make sql
     *
     * @return \ErnandesRS\EasyCrud\Core\Crud
     */
    private function makeSql()
    {
        if ($this->operationType === self::OPERATION_TYPE_SELECT) {
            $this->sql = str_replace([
                "{{_fields_}}",
                "{{_table_}}",
                "{{_conditions_}}"
            ], [
                $this->fields,
                $this->table,
                $this->conditions()
            ], $this->selectSql);
        } else if ($this->operationType === self::OPERATION_TYPE_INSERT) {
            $this->sql = str_replace([
                "{{_table_}}",
                "{{_fields_}}",
                "{{_values_}}"
            ], [
                $this->table,
                implode(", ", array_keys($this->data)),
                implode(", ", array_map(function ($key) {
                    return ":{$key}";
                }, array_keys($this->data)))
            ], $this->insertSql);
        } else if ($this->operationType === self::OPERATION_TYPE_UPDATE) {
            $this->sql = str_replace([
                "{{_table_}}",
                "{{_fields_}}",
                "{{_conditions_}}"
            ], [
                $this->table,
                $this->updateFields(),
                "{$this->primaryKey} = :{$this->primaryKey}"
            ], $this->updateSql);
        }

        return $this;
    }

    /**
     * Conditions array to string
     *
     * @return string
     */
    private function conditions()
    {
        return implode(" ", array_merge([1], array_map(function ($condition) {
            $condStr = [];

            foreach ($condition as $key => $cond) {
                $condStr[] = "{$cond[0]} {$cond[1]} {$cond[2]} :{$cond[1]}_{$key}";
            }

            return implode(" ", $condStr);
        }, $this->conditions)));
    }

    /**
     * Get Update fields
     *
     * @return string
     */
    private function updateFields()
    {
        $arr = [];

        foreach ($this->data as $key => $value) {
            $arr[] = "{$key}=:{$key}";
        }

        return implode(", ", $arr);
    }
}