<?php

namespace Entity;

use Model\Model;

class User
{
    private int $id;
    private string $role;
    private ?Model $model;

    public function __construct(int $id, string $role, ?Model $model)
    {
        $this->id = $id;
        $this->role = $role;
        $this->model = $model;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }
}