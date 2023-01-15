<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use BenConda\Collection\Modifier\Add;
use BenConda\Collection\Modifier\Reindex;
use PHPUnit\Framework\TestCase;

final class AddTest extends TestCase
{
    /**
     * @covers Add
     * @covers Reindex
     */
    public function testAddModifier(): void
    {
        $collection = Collection::from(range(0, 3))
        (
            new Add(range(4, 6))
        )
        (
            new Reindex()
        );

        self::assertSame(range(0, 6), iterator_to_array($collection));
    }
}
