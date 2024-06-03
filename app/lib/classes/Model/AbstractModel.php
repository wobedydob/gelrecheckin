<?php

namespace Model;

use Service\Query;
use Traits\Values;

abstract class AbstractModel
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
            throw new \Exception('Define `protected static string $table` in your model');
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
        $table = static::table();
        return Query::new($table)->with($columns);
    }

    // todo: join

    // todo: insert / update query
//    public function save()
//    {
//        Query::new(self::table())->update($this->values);
//
//        return $this;
//    }

    private function mapResult(false|array $record)
    {
        $this->values = [];

        if (empty($record)) {
            return $this;
        }

        foreach ($record as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

}
