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
     * Where Null
     *
     * @param string $field
     * @return Crud
     */
    protected function whereNull(string $field)
    {
        $this->conditions[$field][] = ["AND", $field, "IS NULL"];
        return $this;
    }

    /**
     * Where Not Null
     *
     * @param string $field
     * @return Crud
     */
    protected function whereNotNull(string $field)
    {
        $this->conditions[$field][] = ["AND", $field, "IS NOT NULL"];
        return $this;
    }

    /**
     * Or Where Null
     *
     * @param string $field
     * @return Crud
     */
    protected function orWhereNull(string $field)
    {
        $this->conditions[$field][] = ["OR", $field, "IS NULL"];
        return $this;
    }

    /**
     * Or Where Not Null
     *
     * @param string $field
     * @return Crud
     */
    protected function orWhereNotNull(string $field)
    {
        $this->conditions[$field][] = ["OR", $field, "IS NOT NULL"];
        return $this;
    }

    /**
     * Limit
     *
     * @param integer $limit
     * @return \ErnandesRS\EasyCrud\Core\Crud
     */
    protected function limit(int $limit = 25)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Offset
     *
     * @param integer $offset
     * @return \ErnandesRS\EasyCrud\Core\Crud
     */
    protected function offset(int $offset)
    {
        $this->offset = $offset;
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

            if ($this->limit != -1) {
                $this->sql .= " LIMIT {$this->limit}";
            } elseif ($this->offset) {
                throw new \Exception("Set a limit to use offset");
            }

            if ($this->offset) {
                $this->sql .= " OFFSET {$this->offset}";
            }
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
        } elseif ($this->operationType === self::OPERATION_TYPE_DELETE) {
            $this->sql = str_replace([
                "{{_table_}}",
                "{{_conditions_}}"
            ], [
                $this->table,
                "{$this->primaryKey}=:{$this->primaryKey}"
            ], $this->deleteSql);
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
                if (in_array($cond[2], ["IS NULL", "IS NOT NULL"])) {
                    $condStr[] = "{$cond[0]} {$cond[1]} {$cond[2]}";
                } else {
                    $condStr[] = "{$cond[0]} {$cond[1]} {$cond[2]} :{$cond[1]}_{$key}";
                }
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