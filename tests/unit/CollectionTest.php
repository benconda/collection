<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Filter;
use BenConda\Collection\Operation\Flip;
use BenConda\Collection\Operation\Map;
use PHPUnit\Framework\TestCase;

/**
 * @covers Collection
 */
final class CollectionTest extends TestCase
{

    public function testGetIterator(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array);
        $arrayResult = iterator_to_array($collection);
        $this->assertSame($array, $arrayResult);
    }

    /**
     * @covers Filter
     */
    public function testWithOperation(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array)
            ->op(new Filter(fn (int $item) => 0 === $item % 2));
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([2, 4, 6, 8, 10], $arrayResult);
    }

    public function testMultipleOperation(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array)
            ->op(new Filter(fn (int $item) => 0 === $item % 2))
            ->op(new Filter(fn (int $item) => $item > 6));
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([8, 10], $arrayResult);
    }

    /**
     * @covers Flip
     */
    public function testFlipOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->op(new Flip());
        $arrayResult = iterator_to_array($collection);
        $this->assertSame(array_flip($array), $arrayResult);
    }

    /**
     * @covers Map
     */
    public function testMapOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->op(new Map(
                fn(int $item): string => "The number is $item"
            ));

        $arrayResult = iterator_to_array($collection);
        $this->assertSame([
            'The number is 1',
            'The number is 2',
            'The number is 3',
            'The number is 4',
        ], $arrayResult);
    }
}