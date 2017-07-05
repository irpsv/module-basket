<?php

namespace basket;

class BasketItem
{
    protected $data;
    protected $count;

    public function __construct($data, int $count = 1)
    {
        $this->data = $data;
        $this->count = $count;
    }

    public function addCount(int $count)
    {
        $this->count += $count;
    }

    public function setCount(int $count)
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getData()
    {
        return $this->data;
    }
}
