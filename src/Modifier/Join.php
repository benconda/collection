<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use BenConda\Collection\Collection;

/**
 * @template TKey
 * @template TValueIterable
 * @template TCollectionKey
 * @template TCollectionValue
 * @template TReturnValue
 *
 * @implements ModifierInterface<TKey, TReturnValue>
 */
final class Join implements ModifierInterface
{
    /**
     * @param iterable<TCollectionKey, TCollectionValue>                                           $collection
     * @param \Closure(TValueIterable, TCollectionValue): bool                                     $on
     * @param \Closure(TValueIterable, Collection<TCollectionKey, TCollectionValue>): TReturnValue $select
     */
    public function __construct(
        private iterable $collection,
        private \Closure $on,
        private \Closure $select,
    ) {
    }

    /**
     * @param iterable<TKey, TValueIterable> $iterable
     *
     * @return \Generator<TKey, TReturnValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $item) {
            yield $key => ($this->select)($item, Collection::from($this->collection)
                ->filter(fn ($withItem) => ($this->on)($item, $withItem))
            );
        }
    }
}
