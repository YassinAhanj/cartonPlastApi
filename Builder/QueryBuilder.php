<?php

namespace App\Builder;


class QueryBuilder
{
    private $table;

    private $pdo;

    private $bindings = [];

    private $where = '';

    private $select = '*';

    private $limit = '';

    private $offset = '';

    private $orderBy = '';

    private $groupBy = '';

    private $update = '';

    private $values = [];

    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = Connection::getInstance();
    }

    public function select($fields = '*')
    {
        $this->select = $fields;
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->where .= ($this->where === '' ? 'WHERE ' : 'AND ') . "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }
    public function limit($limit)
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    public function update($data)
    {
        $this->update = 'UPDATE ' . $this->table . ' SET ';
        foreach ($data as $column => $value) {
            $this->update .= "$column = ?, ";
            $this->values[] = $value;
        }
        $this->update = rtrim($this->update, ', ');
        $this->update .= ' ' . $this->where;
        return $this;
    }
    

    public function delete()
    {
        $this->update = 'DELETE FROM ' . $this->table . ' ' . $this->where;
        return $this;
    }


    public function insert($data)
    {
        $columns = '';
        $values = '';
        foreach ($data as $column => $value) {
            $columns .= "$column,";
            $values .= '?,';
            $this->values[] = $value;
        }
        $columns = rtrim($columns, ',');
        $values = rtrim($values, ',');
        $this->update = "INSERT INTO " . $this->table . " ($columns) VALUES ($values)";
        return $this;
    }

    public function get()
    {
        $sql = "SELECT $this->select FROM $this->table $this->where $this->groupBy $this->orderBy $this->limit $this->offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function executeDelete()
    {
        $stmt = $this->pdo->prepare($this->update);
        $stmt->execute($this->bindings);

        $this->bindings = [];
        $this->values = [];

        return $stmt->rowCount();
    }
    public function execute()
    {
        $stmt = $this->pdo->prepare($this->update);
        $stmt->execute($this->values);
        return $stmt->rowCount();
    }

    public function executeUpdate()
    {
        $stmt = $this->pdo->prepare($this->update);

        $stmt->execute(array_merge($this->values, $this->bindings));

        $this->bindings = [];
        $this->values = [];

        return $stmt->rowCount();
    }
}
