<?php

namespace Model;

use Entity\Collection;
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

    private static function table(): string
    {
        if (!isset(static::$table)) {
            throw new MissingPropertyException('Define `protected static string $table` in your model', static::class, 1717407958262);
        }
        return static::$table;
    }

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

    public static function find(string $id, string $primaryKey = null): array|Model|null
    {
        $pk = $primaryKey ?? static::pk();
        return self::where($pk, '=', $id)->first();
    }

    public static function all(int $limit = 0, int $offset = 0, string $orderBy = null, string $orderDirection = 'ASC'): array|Collection
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->all($limit, $offset, $orderBy, $orderDirection);
    }

    public static function where(string $column, string $operator, $value): Query
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->where($column, $operator, $value);
    }

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

    public static function create(array $values, string $primaryKey = null): bool|array
    {
        return Query::new(self::table())->create($values, $primaryKey);
    }

    public static function count(): int
    {
        return Query::new(self::table())->count();
    }

    public function columns(): array
    {
        return static::$columns;
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->columns() as $name => $label) {
            $array[$name] = $this->$name;
        }
        return $array;
    }

    protected static function nextId(string $key = null): string
    {
        $pk = $key ?? static::pk();
        return Query::new(self::table())->nextId($pk);
    }

}
