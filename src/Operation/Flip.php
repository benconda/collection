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
final class Flip implements OperationInterface
{

    /**
     *
     * @param iterable<TValue, TKey> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $value) {
            yield $value => $key;
        }
    }
}