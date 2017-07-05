<?php

namespace basket;

class Basket
{
    protected $hash = [];
    protected $items = [];

    public function addItem(BasketItem $item): void
    {
        $itemId = $this->findItem($item);
        if ($itemId > -1) {
            $this->items[$itemId]->addCount(
                $item->getCount()
            );
        }
        else {
            $key = $this->getHashItem($item);
            $this->items[] = $item;
            $this->hash[$key] = end(array_keys($this->items));
        }
    }

    protected function findItem(BasketItem $item): int
    {
        $key = $this->getHashItem($item);
        return $this->hash[$key] ?? -1;
    }

    protected function getHashItem(BasketItem $item): string
    {
        return serialize($item->getData());
    }

    public function setItem(int $itemId, BasketItem $item): void
    {
        $key = $this->getHashItem($item);
        $oldItemId = $this->findItem($item);
        if ($oldItemId > -1) {
            unset($this->items[$oldItemId]);
        }

        $this->hash[$key] = $itemId;
        $this->items[$itemId] = $item;
    }

    public function getItem(int $itemId): ?BasketItem
    {
        return $this->items[$itemId] ?? null;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getCount(int $itemId): int
    {
        $item = $this->getItem($itemId);
        if (!$item) {
            return -1;
        }
        return $item->getCount();
    }
}
