<?php

namespace tests;

use basket\Basket;
use basket\BasketItem;
use basket\BasketItemNotFoundException;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    public function testAddItem()
    {
        $item = new BasketItem("data");
        $basket = new Basket();
        $basket->addItem($item);

        $items = $basket->getItems();
        $this->assertEquals(
            1,
            count($items)
        );
        $this->assertEquals(
            serialize($item),
            serialize($items[0])
        );
    }

    public function testGetItems()
    {
        $basket = new Basket();
        $this->assertEquals(
            0,
            count($basket->getItems())
        );

        $basket->addItem(
            new BasketItem("data")
        );
        $this->assertEquals(
            1,
            count($basket->getItems())
        );
    }

    /**
     * При добавлении элемента с одинаковыми данными:
     *  - увеличивается количество
     *  - элемент не добавляется
     */
    public function testAddDublicateItem()
    {
        $item1 = new BasketItem("data");
        $item2 = new BasketItem("data2", 5);
        $basket = new Basket();
        $basket->addItem($item1);
        $basket->addItem(
            new BasketItem("data", 3)
        );
        $basket->addItem($item2);
        $basket->addItem($item2);

        $items = $basket->getItems();
        $this->assertEquals(
            2,
            count($items)
        );
        $this->assertEquals(
            serialize($item1->getData()),
            serialize($items[0]->getData())
        );
        $this->assertEquals(
            4,
            $items[0]->getCount()
        );
        $this->assertEquals(
            10,
            $items[1]->getCount()
        );
    }

    /**
     * Транзитивность получения количества
     */
    public function testCount()
    {
        $item = new BasketItem("data", 5);
        $basket = new Basket();
        $basket->addItem($item);

        $this->assertEquals(
            $item->getCount(),
            $basket->getCount(0)
        );
    }

    /**
     * При установке элемента, ID продожается с максимального ключа (как в массиве)
     */
    public function testSetItem()
    {
        $item1 = new BasketItem("data1");
        $item2 = new BasketItem("data2");
        $item3 = new BasketItem("data3");

        $basket = new Basket();
        $basket->addItem($item1);
        $basket->setItem(3, $item2);
        $basket->addItem($item3);

        $items = array_keys($basket->getItems());
        $this->assertEquals(
            0,
            $items[0]
        );
        $this->assertEquals(
            3,
            $items[1]
        );
        $this->assertEquals(
            4,
            $items[2]
        );
    }

    /**
     * При установке элемента, который уже присутствует в корзине,
     * увеличивается количество уже имеющегося элемента
     */
    public function testSetItemDuplicate()
    {
        $item1 = new BasketItem("data1");
        $item2 = new BasketItem("data2");

        $basket = new Basket();
        $basket->addItem($item1);
        $basket->addItem($item2);
        $basket->setItem(3, $item1);

        $items = $basket->getItems();
        $keys = array_keys($items);
        $this->assertEquals(
            2,
            count($items)
        );
        $this->assertEquals(
            1,
            $keys[0]
        );
        $this->assertEquals(
            3,
            $keys[1]
        );
        $this->assertEquals(
            serialize($item1),
            serialize($items[3])
        );
        $this->assertEquals(
            serialize($item2),
            serialize($items[1])
        );
    }

    public function testGetItem()
    {
        $basket = new Basket();
        $basket->addItem(new BasketItem("data1"));
        $basket->addItem(new BasketItem("data2"));
        $basket->setItem(10, new BasketItem("data3"));

        $items = $basket->getItems();
        $this->assertEquals(
            serialize($items[0]),
            serialize($basket->getItem(0))
        );
        $this->assertEquals(
            serialize($items[1]),
            serialize($basket->getItem(1))
        );
        $this->assertNull($basket->getItem(2));
        $this->assertFalse(isset($items[2]));
        $this->assertEquals(
            serialize($items[10]),
            serialize($basket->getItem(10))
        );
    }

    public function testGetCountNotFoundItem()
    {
        $basket = new Basket();
        $basket->addItem(new BasketItem("data", 5));

        $this->assertEquals(
            5,
            $basket->getCount(0)
        );
        $this->assertEquals(
            -1,
            $basket->getCount(1)
        );
    }
}
