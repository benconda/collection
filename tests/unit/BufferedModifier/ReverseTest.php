<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\BufferedModifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

final class ReverseTest extends TestCase
{
    /**
     * @covers \Reverse
     */
    public function testReverse(): void
    {
        $collection = Collection::from(range(0, 3))
            ->reverse();

        self::assertSame([
            3 => 3,
            2 => 2,
            1 => 1,
            0 => 0,
        ], $collection->toArray());
    }
}
