<?php

namespace ErnandesRS\EasyCrud\Core;

class Crud
{
    use CrudTrait;

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
     * Limit
     *
     * @var integer
     */
    protected int $limit;

    /**
     * Offset
     *
     * @var null|integer
     */
    protected null|int $offset;

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
        $this->limit = -1;
        $this->offset = null;
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
     * Insert
     *
     * @param array $data
     * @return null|Crud
     */
    protected function insert(array $data)
    {
        $this->operationType = self::OPERATION_TYPE_INSERT;
        $this->data = $data;

        $statement = $this->connection
            ->getPdo()
            ->prepare($this->makeSql()->sql);

        $statement = $this->insertUpdateBind($statement);

        if (!$statement->execute()) {
            return null;
        }

        $this->data[$this->primaryKey] = $this->connection->getPdo()->lastInsertId();

        return $this;
    }

    /**
     * Update
     *
     * @param array $data
     * @return null|Crud
     */
    protected function update(array $data)
    {
        $this->operationType = self::OPERATION_TYPE_UPDATE;

        foreach ($data as $k => $d) {
            $this->data[$k] = $d;
        }

        $statement = $this->connection
            ->getPdo()
            ->prepare($this->makeSql()->sql);

        $statement = $this->insertUpdateBind($statement);

        if (!$statement->execute()) {
            return null;
        }

        return $this;
    }

    /**
     * Delete
     *
     * @return bool
     */
    public function delete()
    {
        $this->operationType = self::OPERATION_TYPE_DELETE;

        $statement = $this->connection
            ->getPdo()
            ->prepare($this->makeSql()->sql);

        $statement->bindParam(":{$this->primaryKey}", $this->data[$this->primaryKey]);

        return $statement->execute();
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
     * Bind(insert/update)
     *
     * @param \PDOStatement $stmt
     * @return \PDOStatement
     */
    private function insertUpdateBind(\PDOStatement $stmt)
    {
        foreach ($this->data as $param => $value) {
            $paramType = \PDO::PARAM_STR;

            if (is_null($value)) {
                $paramType = \PDO::PARAM_NULL;
            } else if (is_bool($value)) {
                $paramType = \PDO::PARAM_BOOL;
            } else if (is_int($value)) {
                $paramType = \PDO::PARAM_INT;
            }

            if (!$stmt->bindValue(":{$param}", $value, $paramType)) {
                throw new \Exception("Fail on bind :{$param} with value {$value}. Fail info: " . $stmt->errorInfo());
            }
        }

        return $stmt;
    }
}