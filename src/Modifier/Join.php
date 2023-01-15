<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use BenConda\Collection\Collection;
use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 * @template TValueFrom
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Join implements ModifierInterface
{
    /**
     * @template TKeyIterable
     * @template TValueIterable
     *
     * @param Collection<TKey, TValue, TKeyIterable, TValueIterable> $collection
     * @param Closure(TValueFrom, TValue): bool $on
     */
    public function __construct(private Collection $collection, private Closure $on, private bool $multiple = false)
    {
    }

    /**
     * @param iterable<TKey, TValueFrom> $iterable
     *
     * @return Generator<TKey, array<TValueFrom|TValue>>
     */
    public function __invoke(iterable $iterable): Generator
    {
        $op = new JoinMultiple(
            new Join\Config(
                collection: $this->collection,
                on: $this->on,
                many: $this->multiple
            )
        );

        yield from ($op)($iterable);
    }
}
