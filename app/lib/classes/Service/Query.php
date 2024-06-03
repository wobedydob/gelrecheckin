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
    private ?AbstractModel $model = null;
    private string $query;
    private string $table;
    private array $columns = ['*'];
    private array $wheres = [];
    private array $params = [];

    private function __construct()
    {
        $this->db = Database::new();
    }

    public static function new(string $table, ?AbstractModel $model = null): Query
    {
        self::$instance = new self();
        self::$instance->table($table);
        self::$instance->model = $model;
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

    /**
     * @throws \Exception
     */
    public function get(): false|array
    {
        $statement = $this->db->bindAndExecute($this->query, $this->params);
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$this->model) {
            return $records;
        }

        return $this->toCollection($records);
    }

    /**
     * @throws \Exception
     */
    public function all(): array
    {
        $query = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $this->query = $query;
        return $this->get() ?? [];
    }

    /**
     * @throws \Exception
     */
    public function first(): array|AbstractModel|null
    {
        $query = self::SELECT . ' TOP 1 ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $this->query = $query;
        $records = $this->get();
        return $records[0] ?? [];
    }

    public function exists(): bool
    {
        $query = self::SELECT . ' 1 FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return (bool)$statement->fetchColumn();
    }


    // todo: join

    private function validateTable(string $table): void
    {
        if (!$this->db->tableExists($table)) {
            throw new InvalidTableException("Table $table does not exist.");
        }
    }

//    public function update()
//    {
//        $query = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $this->map($this->columns));
//        if (!$this->wheres) {
////            throw new \Exceptions\QueryException('No where clause provided'); // todo: fix
//        }
//        if (!empty($this->wheres)) {
//            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
//        }
//        $this->db->bindAndExecute($query, $this->params);
//    }

//    // todo: what???
//    public function map(array $columnValues)
//    {
//        $result = [];
//
//        foreach ($columnValues as $field => $value) {
//            if (is_string($value)) {
//                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
//            } else if (is_bool($value)) {
//                $value = $value ? 1 : 0;
//            } else if (is_int($value)) {
//                $value = (int)$value;
//            } else {
//                $value = $value;
//            }
//            $result[] = $field . '=' . $value;
//        }
//
//        return $result;
//    }

    /**
     * @throws \Exception
     */
    private function toCollection(array $records): array
    {
        $result = [];

        foreach ($records as $record) {
            $result[] = $this->toModel($record);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    private function toModel(array $record): ?AbstractModel
    {
        if (!$this->hasModel()) {
            throw new \Exception('No model defined'); // todo: proper exception
        }

        $model = new $this->model;
        foreach ($record as $field => $property) {
            $model->$field = $property;
        }
        return $model;
    }

    private function hasModel(): bool
    {
        return $this->model !== null;
    }

}
