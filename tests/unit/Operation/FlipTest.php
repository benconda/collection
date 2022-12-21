<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Operation;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Flip;
use PHPUnit\Framework\TestCase;

final class FlipTest extends TestCase
{
    /**
     * @covers Flip
     */
    public function testFlipOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->apply(new Flip());
        $arrayResult = iterator_to_array($collection);
        $this->assertSame(array_flip($array), $arrayResult);
    }
}
