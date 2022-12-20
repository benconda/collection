<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class Each implements OperationInterface
{
    /**
     * @param Closure(TValue): void $callback
     */
    public function __construct(private Closure $callback)
    {
    }

    /**
     *
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $value) {
            ($this->callback)($value);
            yield $key => $value;
        }
    }

}