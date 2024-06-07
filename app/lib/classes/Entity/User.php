<?php

namespace Entity;

class User
{
    private int $id;
    private string $role;
    private array $attributes = [];

    public function __construct(int $id, string $role)
    {
        $this->id = $id;
        $this->role = $role;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}