<?php

namespace DevCoder;

use DevCoder\Interfaces\QueryInterface;

class Select implements QueryInterface
{
    /**
     * @var \PDO|null
     */
    private $pdo;

    /**
     * @var array<string>
     */
    private $fields = [];

    /**
     * @var array<string>
     */
    private $conditions = [];

    /**
     * @var array<string>
     */
    private $whereInColumn;

    /**
     * @var array
     */
    private $whereInValues = [];

    /**
     * @var array<string>
     */
    private $whereInKeys = [];

    /**
     * @var integer
     */
    private $whereInCount = 0;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array<string>
     */
    private $order = [];

    /**
     * @var array<string>
     */
    private $from = [];

    /**
     * @var array<string>
     */
    private $innerJoin = [];

    /**
     * @var array<string>
     */
    private $leftJoin = [];

    /**
     * @var int|null
     */
    private $limit;

    public function __construct(\PDO $pdo, array $select)
    {
        $this->fields = $select;
        $this->pdo = $pdo;
    }

    public function select(string ...$select): self
    {
        foreach ($select as $arg) {
            $this->fields[] = $arg;
        }
        return $this;
    }

    public function __toString(): string
    {
        $query_tostring =
            'SELECT ' . implode(', ', $this->fields)
            . ' FROM ' . implode(', ', $this->from)
            . ($this->leftJoin === [] ? '' : ' LEFT JOIN '. implode(' LEFT JOIN ', $this->leftJoin))
            . ($this->innerJoin === [] ? '' : ' INNER JOIN '. implode(' INNER JOIN ', $this->innerJoin))
            . ($this->conditions === [] ? '' : ' WHERE ' . implode(' AND ', $this->conditions));

        if ($this->whereInColumn !== null){
            $query_tostring .= ($this->conditions === [] ? ' WHERE ' : ' AND ');
            for ($i = 0; $i < count($this->whereInColumn)-1; $i++){
                $query_tostring .= $this->whereInColumn[$i] . ' IN (' . $this->whereInKeys[$i] . ') AND ';
            }
            $query_tostring .= $this->whereInColumn[$i] . ' IN (' . $this->whereInKeys[$i] . ')';
        }
        $query_tostring .=
            ($this->order === [] ? '' : ' ORDER BY ' . implode(', ', $this->order))
            . ($this->limit === null ? '' : ' LIMIT ' . $this->limit);
        return $query_tostring;
    }

    public function where(string ...$where): self
    {
        foreach ($where as $arg) {
            $this->conditions[] = $arg;
        }
        return $this;
    }

    public function whereIn(string $columnName, array $arrayIn, $not = false): self
    {
        $this->whereInColumn[] = $columnName;
        $inKeys = "";
        $inValues = array();
        foreach ($arrayIn as $value){
            $key = ":id".++$this->whereInCount;
            $inKeys .= "$key, ";
            $inValues[$key] = $value; // collecting values into key-value array
            //$inKeys = rtrim($inKeys,","); // :id0,:id1,:id2
            $this->params["id".$this->whereInCount] = $value;
        }
        $inKeys = rtrim($inKeys, ", ");
        $this->whereInKeys[] = $inKeys;
        //$this->whereInValues = array_merge($this->whereInValues,$inValues);
        return $this;
    }

    public function from(string $table, ?string $alias = null): self
    {
        $this->from[] = $alias === null ? $table : "${table} AS ${alias}";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy(string ...$order): self
    {
        foreach ($order as $arg) {
            $this->order[] = $arg;
        }
        return $this;
    }

    public function innerJoin(string ...$join): self
    {
        $this->leftJoin = [];
        foreach ($join as $arg) {
            $this->innerJoin[] = $arg;
        }
        return $this;
    }

    public function leftJoin(string ...$join): self
    {
        $this->innerJoin = [];
        foreach ($join as $arg) {
            $this->leftJoin[] = $arg;
        }
        return $this;
    }

    public function params(array $params){
        if ($this->params){
            $this->params = array_merge($this->params, $params);
        }else {
            $this->params = $params;
        }
        return $this;
    }

    public function execute(){
        $query = $this->__toString();
        if ($this->params){
            $pdoStatement = $this->pdo->prepare($query);
            $pdoStatement->execute($this->params);
            return $pdoStatement;
        }
        return $this->pdo->query($query);
    }

    public function get(){
        $query_executed = $this->execute();
        return $query_executed->fetchAll(\PDO::FETCH_ASSOC);
    }
}
