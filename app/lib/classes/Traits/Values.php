<?php

namespace Traits;

trait Values
{
    protected array $values = [];

    /**
     * Magic method to retrieve the value of a dynamic property.
     *
     * @param string $name The name of the property to retrieve.
     * @return mixed|null The value of the property if set, otherwise null.
     */
    public function __get(string $name)
    {
        return $this->values[$name] ?? null;
    }

    /**
     * Magic method to set the value of a dynamic property.
     *
     * @param string $name The name of the property to set.
     * @param mixed $value The value to assign to the property.
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->values[$name] = $value;
    }

    /**
     * Magic method to check if a dynamic property is set.
     *
     * @param string $name The name of the property to check.
     * @return bool True if the property is set, false otherwise.
     */
    public function __isset(string $name): bool
    {
        return isset($this->values[$name]);
    }

}