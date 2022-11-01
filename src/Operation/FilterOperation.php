<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use BenConda\Collection\OperationInterface;
use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class FilterOperation implements OperationInterface
{
    private Closure $filterCallback;

    /**
     * @param Closure(TValue $item): bool $filterCallback
     */
    public function __construct(Closure $filterCallback)
    {
        $this->filterCallback = $filterCallback;
    }

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