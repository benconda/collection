<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use BenConda\Collection\Modifier\Filter;
use BenConda\Collection\Modifier\Map;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BenConda\Collection\Modifier\Aggregate
 */
final class AggregateTest extends TestCase
{
    public function testItAggregateModifiers(): void
    {
        $collection = Collection::from(range(0, 3))
            ->aggregate(
                new Map(fn (int $element) => $element + 5),
                new Filter(fn (int $item) => $item > 1)
            );
        $expectedArray = [
            [0, 5, 2],
            [1, 6, 3],
            [2, 7, null],
            [3, 8, null],
        ];
        self::assertSame($expectedArray, $collection->toArray());
    }
}
