<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Collection;
use BenConda\Collection\Modifier\Debug;
use BenConda\Collection\Modifier\Filter;
use BenConda\Collection\Modifier\Map;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Collection
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
     * @covers \Filter::
     */
    public function testWithModifier(): void
    {
        /** @var int[] $array */
        $array = range(1, 10);

        $collection = (Collection::from($array))(
            new Filter(
                callback: fn (int $item) => 0 === $item % 2
            )
        );
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([2, 4, 6, 8, 10], $arrayResult);
    }

    public function testMultipleModifier(): void
    {
        $array = range(1, 10);
        $collection = (Collection::from($array))(
            new Filter(
                callback: fn (int $item) => 0 === $item % 2
            ),
        )(
            new Filter(
                callback: fn (int $item) => $item > 6
            )
        );

        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([8, 10], $arrayResult);
    }

    public function testModifierGenerators(): void
    {
        $debugModifier = new Debug();
        Collection::from(range(1, 10))(
            $debugModifier
        )(
            new Filter(
                callback: fn (int $item): bool => $item >= 5
            ),
        )(
            new Map(
                on: fn (int $item): string => "The number is $item"
            )
        )(
            $debugModifier
        )->first();
        $debugLog = $debugModifier->getDebugLog();

        self::assertCount(6, $debugLog);
        $this->assertSame([
            ['key' => 0, 'value' => 1],
            ['key' => 1, 'value' => 2],
            ['key' => 2, 'value' => 3],
            ['key' => 3, 'value' => 4],
            ['key' => 4, 'value' => 5],
            ['key' => 4, 'value' => 'The number is 5'],
        ], $debugLog);
    }

    public function testFromGeneratorFail(): void
    {
        $generator = static fn (): \Generator => yield from range(0, 3);
        $col = Collection::from($generator());
        self::assertSame([0, 1, 2, 3], $col->toArray());
        $this->expectExceptionMessage('Generator passed to yield from was aborted without proper return and is unable to continue');
        $col->execute();
    }

    public function testFromGeneratorSuccessWithCache(): void
    {
        $generator = static fn (): \Generator => yield from range(0, 3);
        $col = Collection::from($generator())
            ->cache();
        self::assertSame([0, 1, 2, 3], $col->toArray());
        self::assertSame([0, 1, 2, 3], $col->toArray());
    }
}
