<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\BufferModifier;

use BenConda\Collection\BufferModifier\Reverse;
use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

final class ReverseTest extends TestCase
{
    /**
     * @covers Reverse
     */
    public function testReverse(): void
    {
        $collection = Collection::from(range(0, 3))
        (
            new Reverse()
        );
        $array = iterator_to_array($collection);
        self::assertSame([
            3 => 3,
            2 => 2,
            1 => 1,
            0 => 0,
        ], $array);
    }
}
