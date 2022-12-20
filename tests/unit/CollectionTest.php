<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Debug;
use BenConda\Collection\Operation\Filter;
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
     * @covers Filter::
     */
    public function testWithOperation(): void
    {
        /** @var int[] $array */
        $array = range(1, 10);

        $collection = (Collection::from($array))
        (
            new Filter(
                callback: fn(int $item) => 0 === $item % 2
            )
        );
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([2, 4, 6, 8, 10], $arrayResult);
    }

    public function testMultipleOperation(): void
    {
        $array = range(1, 10);
        $collection = (Collection::from($array))
        (
            new Filter(
                callback: fn(int $item) => 0 === $item % 2
            ),
        )
        (
            new Filter(
                callback: fn(int $item) => $item > 6
            )
        );

        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([8, 10], $arrayResult);
    }

    public function testOperationGenerators(): void
    {
        $debugOperation = new Debug();
        Collection::from(range(1, 10))
        (
            $debugOperation
        )
        (
            new Filter(
                callback: fn(int $item): bool => $item > 5
            ),
        )
        (
            new Map(
                callback: fn(int $item): string => "The number is $item"
            )
        )
        (
            $debugOperation
        )
        ->first();
        self::assertCount(7, $debugOperation->getDebugLog());
    }
}