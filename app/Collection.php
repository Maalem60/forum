<?php
namespace App;

class Collection implements \IteratorAggregate, \Countable {

    private array $items;

    public function __construct(array $items = []) {
        $this->items = $items;
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->items);
    }

    public function count(): int {
        return count($this->items);
    }
}
