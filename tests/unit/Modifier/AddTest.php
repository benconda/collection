<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use BenConda\Collection\Modifier\Add;
use BenConda\Collection\Modifier\Reindex;
use PHPUnit\Framework\TestCase;

/**
 * @covers Add
 * @covers Reindex
 */
final class AddTest extends TestCase
{
    public function testAddModifier(): void
    {
        $collection = Collection::from(range(0, 3))
            ->add(range(4, 6))
            ->reindex();

        self::assertSame(range(0, 6), $collection->toArray());
    }
}
