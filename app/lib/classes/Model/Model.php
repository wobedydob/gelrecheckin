<?php

namespace Model;

use Exceptions\MissingPropertyException;
use Service\Query;
use Traits\Values;

abstract class Model
{
    use Values;

    protected static string $table;

    private static function table()
    {
        self::validate();
        return static::$table;
    }

    private static function validate(): void
    {
        if (!isset(static::$table)) {
            throw new MissingPropertyException('Define `protected static string $table` in your model', static::class, 1717407958262);
        }
    }

    public static function all()
    {
        $model = static::class;
        $table = static::table();
        return Query::new($table, (new $model))->all();
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

    public static function create(array $values)
    {
        return Query::new(self::table())->create($values);
    }


}
