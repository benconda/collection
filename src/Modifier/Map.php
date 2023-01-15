<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 * @template TValueIterable
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Map implements ModifierInterface
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
