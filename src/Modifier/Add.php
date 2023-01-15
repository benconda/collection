<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Add implements ModifierInterface
{
    /**
     * @param iterable<TKey, TValue> $items
     */
    public function __construct(private iterable $items)
    {
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        yield from $iterable;
        yield from $this->items;
    }
}
