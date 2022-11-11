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
final class First implements OperationInterface
{

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $value) {
            yield $key => $value;

            return;
        }
    }
}