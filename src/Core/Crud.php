<?php

namespace ErnandesRS\EasyCrud\Core;

class Crud
{
    /**
     * Select operation
     */
    private const OPERATION_TYPE_SELECT = 1;

    /**
     * Insert operation
     */
    private const OPERATION_TYPE_INSERT = 2;

    /**
     * Update operation
     */
    private const OPERATION_TYPE_UPDATE = 3;

    /**
     * Delete operation
     */
    private const OPERATION_TYPE_DELETE = 4;

    /**
     * Connection
     *
     * @var Connector
     */
    private Connector $connection;

    /**
     * Table name
     *
     * @var string
     */
    protected string $table;

    /**
     * Primary key
     *
     * @var string
     */
    protected string $primaryKey;

    /**
     * Select Sql
     *
     * @var string
     */
    private string $selectSql;

    /**
     * Insert Sql
     *
     * @var string
     */
    private string $insertSql;

    /**
     * Update Sql
     *
     * @var string
     */
    private string $updateSql;

    /**
     * Delete Sql
     *
     * @var string
     */
    private string $deleteSql;

    /**
     * Operation type
     *
     * @var int
     */
    private int $operationType;

    /**
     * Sql
     *
     * @var string
     */
    private string $sql;

    /**
     * Fields
     *
     * @var string
     */
    private string $fields;

    /**
     * Conditions
     * 
     * @var array
     */
    private array $conditions;

    /**
     * Conditions
     * 
     * @var array
     */
    private array $values;

    /**
     * Data
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Constructor
     *
     * @param string $table
     * @param string $primaryKey
     */
    public function __construct(string $table, string $primaryKey = "id")
    {
        $this->connection = (new Connector())->connect(
            CONF_EASY_CRUD["dbname"],
            CONF_EASY_CRUD["host"],
            CONF_EASY_CRUD["user"],
            CONF_EASY_CRUD["pass"],
            CONF_EASY_CRUD["options"]
        );

        $this->table = $table;
        $this->primaryKey = $primaryKey;

        $this->selectSql = "SELECT {{_fields_}} FROM {{_table_}} WHERE {{_conditions_}}";
        $this->insertSql = "INSERT INTO {{_table_}} ({{_fields_}}) VALUES ({{_values_}})";
        $this->updateSql = "UPDATE {{_table_}} SET {{_fields_}} WHERE {{_conditions_}}";
        $this->deleteSql = "DELETE FROM {{_table_}} WHERE {{_conditions_}}";
        $this->operationType = self::OPERATION_TYPE_SELECT;
        $this->sql = "";
        $this->fields = "*";
        $this->conditions = [];
        $this->values = [];
    }

    /**
     * Select
     *
     * @param string $fields
     * @return Crud
     */
    protected function select(string $fields = "*")
    {
        $this->operationType = self::OPERATION_TYPE_SELECT;
        $this->fields = $fields;
        return $this;
    }

    /**
     * Where
     *
     * @param string $field
     * @param string $operator
     * @param string|integer|float $value
     * @return Crud
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
     * @return Crud
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
     * Fetch
     *
     * @return Crud
     */
    protected function fetch()
    {
        $statement = $this->connection
            ->getPdo()
            ->prepare($this->makeSql()->sql);

        foreach ($this->values as $values) {

            $currentParam = null;
            $paramNum = 0;

            foreach ($values as $val) {
                $param = $val[0];
                $value = $val[1];
                $paramType = \PDO::PARAM_STR;

                if ($param != $currentParam) {
                    $currentParam = $param;
                } else {
                    $paramNum++;
                }

                if (is_null($value)) {
                    $paramType = \PDO::PARAM_NULL;
                } else if (is_bool($value)) {
                    $paramType = \PDO::PARAM_BOOL;
                } else if (is_int($value)) {
                    $paramType = \PDO::PARAM_INT;
                }

                if (!$statement->bindValue(":{$param}_{$paramNum}", $value, $paramType)) {
                    throw new \Exception("Fail on bind :{$paramNum} with value {$value}. Fail info: " . $statement->errorInfo());
                }
            }
        }

        if ($statement->execute()) {
            $this->data = $statement->fetchAll(\PDO::FETCH_CLASS, get_called_class(), [$this->table, $this->primaryKey]);
        }

        return $this;
    }

    /**
     * Make sql
     *
     * @return Crud
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
}