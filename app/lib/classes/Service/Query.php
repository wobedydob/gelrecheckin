<?php

namespace Service;

use Entity\Collection;
use Enums\PDOError;
use Exceptions\DuplicateKeyException;
use Exceptions\InvalidColumnException;
use Exceptions\InvalidTableException;
use Exceptions\MissingPropertyException;
use http\Exception\RuntimeException;
use Model\Model;
use PDO;
use Util\StringHelper;

class Query
{
    private const string SELECT = 'SELECT';
    private const string INSERT = 'INSERT INTO';
    private const string UPDATE = 'UPDATE';

    private static ?Query $instance = null;
    private Database $db;
    private ?Model $model = null;
    private string $query;
    private string $table;
    private array $columns = ['*'];
    private array $wheres = [];
    private array $params = [];

    private function __construct()
    {
        $this->db = Database::instance();
    }

    public static function new(string $table, ?Model $model = null): Query
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

    public function count(): int
    {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table;

        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $statement = $this->db->bindAndExecute($query, $this->params);

        if ($statement === false) {
            throw new \Exception("Count query failed to execute.");
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    /**
     * Insert a new record into the table.
     *
     * @param array $data
     * @return array|bool
     * @throws \Exception
     */
    public function create(array $data, string $primaryKey = null): bool|array
    {
        $this->columns = array_keys($data);

        if($primaryKey) {
            $data[$primaryKey] = $this->nextId($primaryKey);
        }

        $this->columns = array_keys($data);
        $placeholders = array_fill(0, count($this->columns), '?');
        $columns = StringHelper::arrayToString($this->columns);
        $placeholders = StringHelper::arrayToString($placeholders);

        $this->query = self::INSERT . ' ' . $this->table . ' (' . $columns . ') VALUES (' . $placeholders . ')';
        $this->params = array_values($data);

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        return $statement && $statement->rowCount() > 0;
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

        return $statement && $statement->rowCount() > 0;
    }

    public function delete(): bool|string|array
    {
        if (empty($this->wheres)) {
            throw new \Exception("No conditions specified for delete");
        }

        $whereString = implode(' AND ', $this->wheres);
        $this->query = 'DELETE FROM ' . $this->table . ' WHERE ' . $whereString;

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        return $statement && $statement->rowCount() > 0;
    }


    /**
     * @throws \Exception
     */
    public function get(int $limit = null, int $offset = null): null|array|Collection
    {
        $statement = $this->db->bindAndExecute($this->query, $this->params);

        if (!$statement) {
            return null;
        }

        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$this->model) {
            return $records;
        }

        return $this->toCollection($records, $limit, $offset);
    }

    /**
     * @throws \Exception
     */
    public function all(int $limit = null, int $offset = null, string $orderBy = null, string $orderDirection = 'ASC'): array|Collection
    {
        $query = self::SELECT . ' ';
        $query .= implode(', ', $this->columns) . ' FROM ' . $this->table;

        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $orderDirection;
        } else {
            $query .= ' ORDER BY (SELECT NULL)';
        }

        if ($offset) {
            $query .= ' OFFSET ' . $offset . ' ROWS';

            if ($limit) {
                $query .= ' FETCH NEXT ' . $limit . ' ROWS ONLY';
            }

        } elseif ($limit) {
            $query .= ' OFFSET 0 ROWS FETCH NEXT ' . $limit . ' ROWS ONLY';
        }

        $this->query = $query;
        return $this->get($limit, $offset) ?? [];
    }

    /**
     * @throws \Exception
     */
    public function first(): array|Model|null
    {
        $query = self::SELECT . ' TOP 1 ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $this->query = $query;
        $records = $this->get();

        if ($records instanceof Collection) {
            return $records->first();
        }

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

    public function max(string $column): int
    {
        $query = self::SELECT . ' MAX(' . $column . ') FROM ' . $this->table;
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return (int)$statement->fetchColumn();
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
    private function toCollection(array $records, int $limit = null, int $offset = null): Collection
    {
        $result = new Collection();

        if ($limit) {
            $result->setLimit($limit);
        }

        if ($offset) {
            $result->setOffset($offset);
        }

        foreach ($records as $record) {
            $result->addToCollection(
                $this->toModel($record)
            );
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    private function toModel(array $record): ?Model
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

    public function nextId(string $key): string
    {
        $pkQuery = self::SELECT . ' MAX(' . $key . ') FROM ' . $this->table;
        $lastId = $this->db->bindAndExecute($pkQuery)->fetch(PDO::FETCH_LAZY);

        if(!isset($lastId[''])) {
            ErrorHandler::log(
                new RuntimeException('Could not get last inserted ID', 1717865333174),
            );
        }

        return $lastId[''] + 1;
    }

}
