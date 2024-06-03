<?php

namespace Traits;

trait Values
{
    protected array $values = [];

    public function __get(string $name)
    {
        return $this->values[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->values[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->values[$name]);
    }

}