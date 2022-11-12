<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Operation;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Each;
use BenConda\Collection\Operation\Map;
use PHPUnit\Framework\TestCase;

final class EachTest extends TestCase
{

    /**
     * @covers Each::
     */
    public function testEachOperation(): void
    {
        $collection = Collection::from(range(0, 10))
        (
            new Map(static fn(int $number) => new FakeObject((string)$number))
        )
        (
            new Each(function (FakeObject $object) {
                $object->setAttribute('New item ' . $object->getAttribute());
            })
        );
        /**
         * @var string $key
         * @var FakeObject $item
         */
        foreach ($collection as $key => $item) {
            $this->assertSame('New item ' . $key, $item->getAttribute());
        }
    }
}

class FakeObject {

    public function __construct(private string $attribute)
    {
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    /**
     * @param string $attribute
     * @return FakeObject
     */
    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

}