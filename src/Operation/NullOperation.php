<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use BenConda\Collection\OperationInterface;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class NullOperation implements OperationInterface
{

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        yield from $iterable;
    }
}