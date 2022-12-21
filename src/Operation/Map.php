<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 * @template TValueIterable
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class Map implements OperationInterface
{
    /**
     * @param Closure(TValueIterable): TValue $callback
     */
    public function __construct(private Closure $callback)
    {
    }

    /**
     *
     * @param iterable<TKey, TValueIterable> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $value) {
            yield $key => ($this->callback)($value);
        }
    }
}
