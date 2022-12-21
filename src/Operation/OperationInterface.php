<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use Generator;

/**
 * @template TKey
 * @template TValue
 */
interface OperationInterface
{
    /**
     * @template TKeyIterable
     * @template TValueIterable
     *
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator;
}
