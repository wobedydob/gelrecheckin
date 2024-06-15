<?php

namespace Service;

use Entity\Collection;
use Exception;
use Exceptions\InvalidTableException;
use Exceptions\MissingPropertyException;
use http\Exception\RuntimeException;
use Model\Model;
use PDO;
use Util\Text;

class Query
{
    private const string SELECT = 'SELECT';
    private const string INSERT = 'INSERT INTO';
    private const string UPDATE = 'UPDATE';
    private const string DELETE = 'DELETE';

    private const string INNER_JOIN = 'INNER JOIN';
    private const string LEFT_JOIN = 'LEFT JOIN';
    private const string RIGHT_JOIN = 'RIGHT JOIN';

    private static ?Query $instance = null;
    private Database $db;
    private ?Model $model = null;
    private string $query;
    private string $table;
    private array $columns = ['*'];
    private array $wheres = [];
    private array $params = [];
    private array $joins = [];

    private function __construct()
    {
        $this->db = Database::instance();
    }

    /**
     * Creates a new instance of Query.
     *
     * @param string $table The name of the table for the query.
     * @param Model|null $model Optional model class for mapping query results.
     * @return Query The Query instance.
     */
    public static function new(string $table, ?Model $model = null): Query
    {
        self::$instance = new self();
        self::$instance->table($table);
        self::$instance->model = $model;
        return self::$instance;
    }

    /**
     * Sets the main table for the query.
     *
     * @param string $table The name of the table.
     * @throws InvalidTableException If the specified table does not exist.
     */
    private function table(string $table): void
    {
        $this->validateTable($table);
        $this->table = $table;
    }

    /**
     * Specifies columns to retrieve in the SELECT query.
     *
     * @param array $columns The columns to select.
     * @return Query The Query instance.
     */
    public function with(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Adds a WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @return Query The Query instance.
     */
    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->params[] = $value;
        return $this;
    }

    /**
     * Adds an OR WHERE condition to the query.
     *
     * @param string $column The column name.
     * @param string $operator The comparison operator.
     * @param mixed $value The value to compare against.
     * @return Query The Query instance.
     */
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
     * Adds a JOIN clause to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $firstColumn The column in the current table.
     * @param string $operator The comparison operator.
     * @param string $secondColumn The column in the joined table.
     * @param string $type The type of join (INNER JOIN, LEFT JOIN, RIGHT JOIN).
     * @return Query The Query instance.
     */
    public function join(string $table, string $firstColumn, string $operator, string $secondColumn, string $type = self::INNER_JOIN): self
    {
        $joinClause = "$type $table ON $firstColumn $operator $secondColumn";
        $this->joins[] = $joinClause;
        return $this;
    }

    /**
     * Adds a LEFT JOIN clause to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $firstColumn The column in the current table.
     * @param string $operator The comparison operator.
     * @param string $secondColumn The column in the joined table.
     * @return Query The Query instance.
     */
    public function leftJoin(string $table, string $firstColumn, string $operator, string $secondColumn): self
    {
        return $this->join($table, $firstColumn, $operator, $secondColumn, self::LEFT_JOIN);
    }

    /**
     * Adds a RIGHT JOIN clause to the query.
     *
     * @param string $table The name of the table to join.
     * @param string $firstColumn The column in the current table.
     * @param string $operator The comparison operator.
     * @param string $secondColumn The column in the joined table.
     * @return Query The Query instance.
     */
    public function rightJoin(string $table, string $firstColumn, string $operator, string $secondColumn): self
    {
        return $this->join($table, $firstColumn, $operator, $secondColumn, self::RIGHT_JOIN);
    }

    /**
     * Counts the number of rows matching the query criteria.
     *
     * @return int The count of matching rows.
     * @throws Exception If the count query fails to execute.
     */
    public function count(): int
    {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table;

        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $statement = $this->db->bindAndExecute($query, $this->params);

        if ($statement === false) {
            throw new Exception("Count query failed to execute.");
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    /**
     * Inserts a new row into the database table.
     *
     * @param array $data The data to insert.
     * @param string|null $primaryKey Optional primary key name.
     * @return bool|array True on success, false on failure, or array for batch insert.
     */
    public function create(array $data, string $primaryKey = null): bool|array
    {
        $this->columns = array_keys($data);

        if($primaryKey) {
            $data[$primaryKey] = $this->nextId($primaryKey);
        }

        $this->columns = array_keys($data);
        $placeholders = array_fill(0, count($this->columns), '?');
        $columns = Text::arrayToString($this->columns);
        $placeholders = Text::arrayToString($placeholders);

        $this->query = self::INSERT . ' ' . $this->table . ' (' . $columns . ') VALUES (' . $placeholders . ')';
        $this->params = array_values($data);

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        return $statement && $statement->rowCount() > 0;
    }

    /**
     * Constructs the SELECT query.
     *
     * @param int|null $limit The maximum number of rows to return.
     * @param int|null $offset The number of rows to skip.
     * @param string|null $orderBy The column to order by.
     * @param string $orderDirection The order direction (ASC or DESC).
     * @return string The generated SQL query.
     */
    private function read(int $limit = null, int $offset = null, string $orderBy = null, string $orderDirection = 'ASC'): string
    {
        $query = self::SELECT . ' ';
        $query .= implode(', ', $this->columns) . ' FROM ' . $this->table;

        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($orderBy !== null) {
            $query .= ' ORDER BY ' . $this->table . '.' . $orderBy . ' ' . $orderDirection;
        } else {
            $query .= ' ORDER BY (SELECT NULL)';
        }

        if($offset) {
            $query .= ' OFFSET ' . $offset . ' ROWS';

            if($limit) {
                $query .= ' FETCH NEXT ' . $limit . ' ROWS ONLY';
            }

        } else if ($limit) {
            $query .= ' OFFSET 0 ROWS FETCH NEXT ' . $limit . ' ROWS ONLY';
        }


        $this->query = $query;
        return $query;
    }

    /**
     * Updates rows matching the current WHERE conditions.
     *
     * @param array $data The data to update.
     * @return bool|array|string True on success, false on failure, or array for batch update.
     * @throws Exception If no conditions are specified for update.
     */
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
            throw new Exception("No conditions specified for update");
        }

        $whereString = implode(' AND ', $this->wheres);
        $this->query = self::UPDATE . ' ' . $this->table . ' SET ' . $setString . ' WHERE ' . $whereString;

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        return $statement && $statement->rowCount() > 0;
    }

    /**
     * Deletes rows matching the current WHERE conditions.
     *
     * @return bool|array|string True on success, false on failure, or array for batch delete.
     * @throws Exception If no conditions are specified for delete.
     */
    public function delete(): bool|string|array
    {
        if (empty($this->wheres)) {
            throw new Exception("No conditions specified for delete");
        }

        $whereString = implode(' AND ', $this->wheres);
        $this->query = self::DELETE . ' FROM ' . $this->table . ' WHERE ' . $whereString;

        $statement = $this->db->bindAndExecute($this->query, $this->params);

        return $statement && $statement->rowCount() > 0;
    }

    /**
     * Retrieves records based on the query criteria.
     *
     * @param int|null $limit The maximum number of rows to return.
     * @param int|null $offset The number of rows to skip.
     * @param string|null $orderBy The column to order by.
     * @param string $orderDirection The order direction (ASC or DESC).
     * @return null|array|Collection The retrieved records or null if no records found.
     */
    public function get(int $limit = null, int $offset = null, string $orderBy = null, string $orderDirection = 'ASC'): null|array|Collection
    {
        $this->query = $this->read($limit, $offset, $orderBy, $orderDirection);

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
     * Retrieves all records matching the query criteria.
     *
     * @param int|null $limit The maximum number of rows to return.
     * @param int|null $offset The number of rows to skip.
     * @param string|null $orderBy The column to order by.
     * @param string $orderDirection The order direction (ASC or DESC).
     * @return array|Collection The retrieved records.
     */
    public function all(int $limit = null, int $offset = null, string $orderBy = null, string $orderDirection = 'ASC'): array|Collection
    {
        return $this->get($limit, $offset, $orderBy, $orderDirection);
    }

    /**
     * Retrieves the first record matching the query criteria.
     *
     * @return array|Model|null The first record or null if no records found.
     */
    public function first(): array|Model|null
    {
        $query = self::SELECT . ' TOP 1 ' . implode(', ', $this->columns) . ' FROM ' . $this->table;
        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
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

    /**
     * Checks if records matching the query criteria exist.
     *
     * @return bool True if records exist, false otherwise.
     */
    public function exists(): bool
    {
        $query = self::SELECT . ' 1 FROM ' . $this->table;
        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return (bool)$statement->fetchColumn();
    }

    /**
     * Retrieves the maximum value of a column based on the query criteria.
     *
     * @param string $column The column to find the maximum value.
     * @return int The maximum value of the column.
     */
    public function max(string $column): int
    {
        $query = self::SELECT . ' MAX(' . $column . ') FROM ' . $this->table;
        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        if (!empty($this->wheres)) {
            $query .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $statement = $this->db->bindAndExecute($query, $this->params);
        return (int)$statement->fetchColumn();
    }

    /**
     * Validates if the specified table exists in the database.
     *
     * @param string $table The name of the table to validate.
     * @throws InvalidTableException If the table does not exist.
     */
    private function validateTable(string $table): void
    {
        if (!$this->db->tableExists($table)) {
            throw new InvalidTableException("Table $table does not exist.");
        }
    }

    /**
     * Converts an array of records into a Collection object.
     *
     * @param array $records The array of records.
     * @param int|null $limit The maximum number of rows to return.
     * @param int|null $offset The number of rows to skip.
     * @return Collection The Collection of models.
     * @throws MissingPropertyException
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
     * Converts a record array into a Model object.
     *
     * @param array $record The record data.
     * @return Model|null The instantiated Model object.
     * @throws MissingPropertyException If the model property is missing.
     */
    private function toModel(array $record): ?Model
    {
        if (!$this->hasModel()) {
            throw new MissingPropertyException(null, static::class, 1717408105528);
        }

        $model = new $this->model;
        foreach ($record as $field => $property) {
            $model->$field = $property;
        }
        return $model;
    }

    /**
     * Checks if a model property is set.
     *
     * @return bool True if the model property is set, false otherwise.
     */
    private function hasModel(): bool
    {
        return $this->model !== null;
    }

    /**
     * Retrieves the next ID for a specified key.
     *
     * @param string $key The primary key column name.
     * @return string The next ID value.
     * @throws RuntimeException If the last inserted ID could not be retrieved.
     */
    public function nextId(string $key): string
    {
        $pkQuery = self::SELECT . ' MAX(' . $key . ') FROM ' . $this->table;
        $lastId = $this->db->bindAndExecute($pkQuery)->fetch(PDO::FETCH_LAZY);

        if(!isset($lastId[''])) {
            Error::log(
                new RuntimeException('Could not get last inserted ID', 1717865333174),
            );
        }

        return $lastId[''] + 1;
    }
}
