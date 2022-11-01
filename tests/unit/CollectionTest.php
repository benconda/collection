<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\FilterOperation;
use BenConda\Collection\Operation\FlipOperation;
use BenConda\Collection\Operation\MapOperation;
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
     * @covers FilterOperation
     */
    public function testWithOperation(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array)
            ->op(new FilterOperation(fn (int $item) => 0 === $item % 2));
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([2, 4, 6, 8, 10], $arrayResult);
    }

    public function testMultipleOperation(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array)
            ->op(new FilterOperation(fn (int $item) => 0 === $item % 2))
            ->op(new FilterOperation(fn (int $item) => $item > 6));
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([8, 10], $arrayResult);
    }

    /**
     * @covers FlipOperation
     */
    public function testFlipOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->op(new FlipOperation());
        $arrayResult = iterator_to_array($collection);
        $this->assertSame(array_flip($array), $arrayResult);
    }

    /**
     * @covers MapOperation
     */
    public function testMapOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->op(new MapOperation(fn(int $item): string => "The number is $item"));

        $arrayResult = iterator_to_array($collection);
        $this->assertSame([
            'The number is 1',
            'The number is 2',
            'The number is 3',
            'The number is 4',
        ], $arrayResult);
    }
}