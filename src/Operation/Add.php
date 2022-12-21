<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class Add implements OperationInterface
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
