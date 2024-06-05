<?php

namespace Service;

use Enums\PDOError;
use Exceptions\DuplicateKeyException;
use Exceptions\InvalidColumnException;
use Exceptions\InvalidTableException;
use Exceptions\MissingPropertyException;
use Model\AbstractModel;
use PDO;
use Util\StringHelper;

class Query
{
    private const string SELECT = 'SELECT';
    private const string INSERT = 'INSERT INTO';
    private const string UPDATE = 'UPDATE';

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
     * Insert a new record into the table.
     *
     * @param array $data
     * @return array|bool
     * @throws \Exception
     */
    public function create(array $data)
    {
        $this->columns = array_keys($data);
        $placeholders = array_fill(0, count($this->columns), '?');

        $columns = StringHelper::arrayToString($this->columns);
        $placeholders = StringHelper::arrayToString($placeholders);

        $this->query = self::INSERT . ' ' . $this->table . ' (' . $columns . ') VALUES (' . $placeholders . ')';
        $this->params = array_values($data);

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        if ($statement === null) {
            return $this->errors(); // todo: properly display error
        }

        return $statement->rowCount() > 0;
    }

    public function update(array $data): bool|string|array
    {
        $this->columns = array_keys($data);
        $setClauses = [];
        foreach ($this->columns as $column) {
            $setClauses[] = "$column = ?";
        }
        $setString = implode(', ', $setClauses);
        $this->params = array_merge(array_values($data), $this->params);

        if (empty($this->wheres)) {
            throw new \Exception("No conditions specified for update");
        }

        $whereString = implode(' AND ', $this->wheres);
        $this->query = self::UPDATE . ' ' . $this->table . ' SET ' . $setString . ' WHERE ' . $whereString;

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        if ($statement === null) {
            return $this->errors();
        }

        return $statement->rowCount() > 0;
    }

    public function delete(): bool|string|array
    {
        if (empty($this->wheres)) {
            throw new \Exception("No conditions specified for delete");
        }

        $whereString = implode(' AND ', $this->wheres);
        $this->query = 'DELETE FROM ' . $this->table . ' WHERE ' . $whereString;

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        if ($statement === null) {
            return $this->errors();
        }

        return $statement->rowCount() > 0;
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

    private function validateTable(string $table): void
    {
        if (!$this->db->tableExists($table)) {
            throw new InvalidTableException("Table $table does not exist.");
        }
    }

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
            throw new MissingPropertyException(null, static::class, 1717408105528); // todo: proper exception
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

    private function errors()
    {
        $errors = \Service\ErrorHandler::getErrors();

        foreach ($errors as $error) {

            switch ($error->getCode()) {
                case PDOError::DUPLICATE_KEY->getCode():
                    // todo: display duplicate key error
                    $keys = StringHelper::arrayToString($this->params);
                    $table = $this->table;
                    $columns = StringHelper::arrayToString($this->columns);
                    $exception = new DuplicateKeyException($keys, $columns, $table, 1717594126032);

                    return [
                        'error' => 'duplicate key',
                        'code' => $error->getCode(),
                        'message' => $exception->getMessage(),
                    ];

                case PDOError::INVALID_COLUMN->getCode():
                    // todo: display invalid column error
                    $table = $this->table;
                    $columns = StringHelper::arrayToString($this->columns);
                    $exception = new InvalidColumnException($columns, $table, 1717593867022);

                    return [
                        'error' => 'invalid column',
                        'code' => $error->getCode(),
                        'message' => $exception->getMessage(),
                    ];
            }

        }

    }

}
