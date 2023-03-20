<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Map
 */
final class MapTest extends TestCase
{
    public function testMapModifier(): void
    {
        /** @var iterable<int, int> $array */
        $array = range(1, 4);

        $collection = Collection::from($array)
            ->map(on: static fn (int $item, int $key): string => "The number is $item and key is $key");

        $this->assertSame([
            'The number is 1 and key is 0',
            'The number is 2 and key is 1',
            'The number is 3 and key is 2',
            'The number is 4 and key is 3',
        ], $collection->toArray());
    }
}
