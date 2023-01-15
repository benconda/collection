<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Each implements ModifierInterface
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
