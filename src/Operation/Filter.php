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
final class Filter implements OperationInterface
{

    /**
     * @param Closure(TValue $item): bool $filterCallback
     */
    public function __construct(private Closure $filterCallback) {}

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $item) {
            if (($this->filterCallback)($item)) {
                yield $key => $item;
            }
        }
    }

}