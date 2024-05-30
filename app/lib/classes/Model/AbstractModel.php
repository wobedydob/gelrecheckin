<?php

namespace Model;

use Service\Query;

abstract class AbstractModel
{
    protected static string $table;

    private static function table()
    {
        self::validate();
        return static::$table;
    }

    private static function validate(): void
    {
        if (!isset(static::$table)) {
            throw new \Exception('Define `protected static string $table` in your model');
        }
    }

    public static function all()
    {
        $table = static::table();
        return Query::new($table)->all();
    }

    public static function where(string $column, string $operator, $value): Query
    {
        $table = static::table();
        return Query::new($table)->where($column, $operator, $value);
    }

    // todo: join

}
