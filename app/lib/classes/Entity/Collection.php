<?php

namespace Entity;

class Collection implements \Iterator, \Countable
{
    private int $position = 0;
    private int $limit = 0;
    private int $offset = 0;
    private array $collection = [];

    public function __construct(int $limit = 0, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): void
    {
        foreach ($collection as $post) {
            $this->addToCollection($post);
        }
    }

    public function addToCollection(mixed $post): void
    {
        $this->collection[] = $post;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /*
     * Iterator methods
     */
    public function first(): mixed
    {
        if (empty($this->collection)) {
            return null;
        }
        return $this->collection[0];
    }

    public function current(): mixed
    {
        return $this->collection[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    /*
     * Countable methods
     */
    public function count(): int
    {
        return count($this->collection);
    }
}