<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Operation;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Map;
use PHPUnit\Framework\TestCase;

final class MapTest extends TestCase
{
    /**
     * @covers Map
     */
    public function testMapOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)->apply(
            new Map(
                callback: fn(int $item): string => "The number is $item"
            )
        );

        $arrayResult = iterator_to_array($collection);
        $this->assertSame([
            'The number is 1',
            'The number is 2',
            'The number is 3',
            'The number is 4',
        ], $arrayResult);
    }
}