<?php

namespace tests;

use basket\BasketItem;
use PHPUnit\Framework\TestCase;

class BasketItemTest extends TestCase
{
    public function testCount()
    {
        $data = "data";
        $item1 = new BasketItem($data);
        $item2 = new BasketItem($data, 5);
        $item3 = new BasketItem($data);
        $item3->setCount(10);

        $this->assertEquals($item1->getCount(), 1);
        $this->assertEquals($item2->getCount(), 5);
        $this->assertEquals($item3->getCount(), 10);
    }

    public function testAvailableData()
    {
        $data = "data";
        $data2 = 123;
        $data3 = [1,2,3];

        $item1 = new BasketItem($data1);
        $item2 = new BasketItem($data2);
        $item3 = new BasketItem($data3);

        $this->assertEquals(
            $data1,
            $item1->getData()
        );
        $this->assertEquals(
            $data2,
            $item2->getData()
        );
        $this->assertEquals(
            serialize($data3),
            serialize($item3->getData())
        );
    }
}
