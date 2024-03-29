<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

final class EachTest extends TestCase
{
    /**
     * @covers \Each
     */
    public function testEachModifier(): void
    {
        $collection = Collection::from(range(0, 10))
            ->map(on: static fn (int $number) => new FakeObject((string) $number))
            ->each(callback: function (FakeObject $object): void {
                $object->attribute = 'New item '.$object->attribute;
            });

        foreach ($collection as $key => $item) {
            $this->assertSame('New item '.$key, $item->attribute);
        }
    }
}

class FakeObject
{
    public function __construct(public string $attribute)
    {
    }
}
