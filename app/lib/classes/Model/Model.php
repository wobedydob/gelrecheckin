<?php

namespace Model;

use Exceptions\MissingPropertyException;
use Service\Query;
use Traits\Values;

abstract class Model
{
    use Values;

    protected static string $table;
    protected static string $primaryKey = 'id';

    private static function table()
    {
        if (!isset(static::$table)) {
            throw new MissingPropertyException('Define `protected static string $table` in your model', static::class, 1717407958262);
        }
        return static::$table;
    }

    private static function pk(): string
    {
        if (!isset(static::$primaryKey)) {
            throw new MissingPropertyException('Define `protected static string $primaryKey` in your model', static::class, 1717407958261);
        }
        return static::$primaryKey;
    }

    public static function all(int $limit = 0, string $orderBy = null, string $orderDirection = 'ASC'): array
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->all($limit, $orderBy, $orderDirection);
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
        return Query::new($table, (new $model))->with($columns);
    }

    public static function create(array $values, string $primaryKey = null): bool
    {
        $primaryKey = $primaryKey ?? static::pk();
        return Query::new(self::table())->create($values, $primaryKey);
    }

    public static function count(): int
    {
        return Query::new(self::table())->count();
    }


}
