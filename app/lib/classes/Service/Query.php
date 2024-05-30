<?php

namespace Service;

use Exceptions\InvalidTableException;
use Model\AbstractModel;
use PDO;

class Query
{
    private const string SELECT = 'SELECT';

    private static ?Query $instance = null;
    private Database $db;

    private string $table;
    private array $columns = ['*'];
    private array $wheres = [];
    private array $params = [];

    private function __construct()
    {
        $this->db = Database::new();
    }

    public static function new(string $table): Query
    {
        self::$instance = new self();
        self::$instance->table($table);
        return self::$instance;
    }

    private function table(string $table): void
    {
        $this->validateTable($table);
        $this->table = $table;
    }

    public function with(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->params[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, $value): self
    {
        if (empty($this->wheres)) {
            return $this->where($column, $operator, $value);
        }

        $lastCondition = array_pop($this->wheres);
        $this->wheres[] = "($lastCondition OR $column $operator ?)";
        $this->params[] = $value;
        return $this;
    }

    public function all(): array
    {
        $query = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): array|AbstractModel|null
    {
        $query = 'SELECT TOP 1 ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function exists(): bool
    {
        $query = 'SELECT 1 FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return (bool) $statement->fetchColumn();
    }


    // todo: join

    private function validateTable(string $table): void
    {
        if (!$this->db->tableExists($table)) {
            throw new InvalidTableException("Table $table does not exist.");
        }
    }
}
