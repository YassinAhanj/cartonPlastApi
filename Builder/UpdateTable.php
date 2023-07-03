<?php


class QueryBuilder
{
    protected $table;
    protected $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function update($id, $column, $value)
    {
        $query = "UPDATE $this->table SET $column = :value WHERE id = :id";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':value', $value);
        $statement->bindValue(':id', $id);
        $statement->execute();
        return $statement->rowCount();
    }
}
