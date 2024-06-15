<?php

namespace Model;

use Entity\Collection;
use Exception;
use Exceptions\MissingPropertyException;
use Interfaces\ITable;
use Service\Query;
use Traits\Values;

abstract class Model implements ITable
{
    use Values;

    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static array $primaryKeys = ['id'];
    protected static array $columns = [];

    /**
     * Get the table name associated with the model.
     *
     * @return string The table name.
     * @throws MissingPropertyException If $table property is not defined in the model.
     */
    public static function table(): string
    {
        if (!isset(static::$table)) {
            throw new MissingPropertyException('Define `protected static string $table` in your model', static::class, 1717407958262);
        }
        return static::$table;
    }

    /**
     * Get the primary key(s) associated with the model.
     *
     * @return string|array The primary key or array of primary keys.
     * @throws MissingPropertyException If $primaryKey or $primaryKeys properties are not defined in the model.
     */
    public static function pk(): string|array
    {
        if (isset(static::$primaryKey)) {
            return static::$primaryKey;
        }

        if (isset(static::$primaryKeys)) {
            return static::$primaryKeys;
        }

        throw new MissingPropertyException('Define `protected static string $primaryKey` or `protected static array $primaryKeys` in your model', static::class, 1717407958263);
    }

    /**
     * Get the columns defined for the model.
     *
     * @return array The columns defined for the model.
     * @throws MissingPropertyException
     */
    public function columns(): array
    {
        if (!isset(static::$columns)) {
            throw new MissingPropertyException('Define `protected static string $columns` in your model', static::class, 1718475570222);
        }
        return static::$columns;
    }

    /**
     * Find a model by its primary key.
     *
     * @param string $id The value of the primary key.
     * @param string|null $primaryKey Optional. The primary key to use for the lookup.
     * @return array|Model|null The found model instance or null if not found.
     * @throws MissingPropertyException
     */
    public static function find(string $id, string $primaryKey = null): array|Model|null
    {
        $pk = $primaryKey ?? static::pk();
        return self::where($pk, '=', $id)->first();
    }

    /**
     * Create a Query instance with a join clause.
     *
     * @param string $table The table to join with.
     * @param string $column1 The first column for the join.
     * @param string $operator The operator for the join.
     * @param string $column2 The second column for the join.
     * @return Query The Query instance with the join clause.
     * @throws MissingPropertyException
     */
    public static function join(string $table, string $column1, string $operator, string $column2): Query
    {
        $model = static::class;
        return Query::new(static::table(), (new $model))->join($table, $column1, $operator, $column2);
    }

    /**
     * Retrieve all models from the database.
     *
     * @param int $limit Optional. Limit the number of results.
     * @param int $offset Optional. Offset for pagination.
     * @param string|null $orderBy Optional. Column to order by.
     * @param string $orderDirection Optional. Order direction (ASC or DESC).
     * @return array|Collection Array or Collection of models.
     * @throws MissingPropertyException
     */
    public static function all(int $limit = 0, int $offset = 0, string $orderBy = null, string $orderDirection = 'ASC'): array|Collection
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->all($limit, $offset, $orderBy, $orderDirection);
    }

    /**
     * Create a Query instance with a where clause.
     *
     * @param string $column The column to filter by.
     * @param string $operator The operator for comparison.
     * @param mixed $value The value to compare against.
     * @return Query The Query instance with the where clause.
     * @throws MissingPropertyException
     */
    public static function where(string $column, string $operator, mixed $value): Query
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->where($column, $operator, $value);
    }

    /**
     * Create a Query instance with specified columns to select.
     *
     * @param array $columns Array of column names to select.
     * @return Query The Query instance with the specified columns.
     * @throws MissingPropertyException
     */
    public static function with(array $columns): Query
    {
        $model = static::class;
        $table = static::table();

        foreach(static::$columns as $name => $label) {
            if(!in_array($name, $columns)) {
                unset(static::$columns[$name]);
            }
        }

        return Query::new($table, (new $model))->with($columns);
    }

    /**
     * Create a new record in the database table associated with the model.
     *
     * @param array $values Associative array of column names and values to insert.
     * @param string|null $primaryKey Optional. Primary key column name.
     * @return bool|array True if successful, or array of inserted IDs.
     * @throws MissingPropertyException
     */
    public static function create(array $values, string $primaryKey = null): bool|array
    {
        return Query::new(self::table())->create($values, $primaryKey);
    }

    /**
     * Get the count of records in the database table associated with the model.
     *
     * @return int The count of records.
     * @throws MissingPropertyException
     * @throws Exception
     */
    public static function count(): int
    {
        return Query::new(self::table())->count();
    }

    /**
     * Convert the model instance to an associative array.
     *
     * @return array The model instance as an associative array.
     * @throws MissingPropertyException
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->columns() as $name => $label) {
            $array[$name] = $this->$name;
        }
        return $array;
    }

    /**
     * Generate the next ID for the primary key of the model's table.
     *
     * @param string|null $key Optional. The primary key to generate the next ID for.
     * @return string The next ID value.
     * @throws MissingPropertyException
     */
    protected static function nextId(string $key = null): string
    {
        $pk = $key ?? static::pk();
        return Query::new(self::table())->nextId($pk);
    }

}
