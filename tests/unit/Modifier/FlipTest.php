<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

final class FlipTest extends TestCase
{
    /**
     * @covers \Flip
     */
    public function testFlipModifier(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->flip();
        $this->assertSame(array_flip($array), $collection->toArray());
    }
}
