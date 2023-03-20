<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use BenConda\Collection\CoreCollection;

/**
 * @template TKey
 * @template TValueIterable
 * @template TCollectionKey
 * @template TCollectionValue
 * @template TReturnValue
 *
 * @implements ModifierInterface<TKey, list<TCollectionValue>|TCollectionValue|TReturnValue>
 */
final class MapWith implements ModifierInterface
{
    /**
     * @param CoreCollection<TCollectionKey, TCollectionValue>                                 $collection
     * @param \Closure(TValueIterable, TCollectionValue): bool                                 $on
     * @param ?\Closure(TValueIterable, list<TCollectionValue>|TCollectionValue): TReturnValue $map
     */
    public function __construct(
        private CoreCollection $collection,
        private \Closure $on,
        private ?\Closure $map = null,
        private bool $many = false
    ) {
    }

    /**
     * @param iterable<TKey, TValueIterable> $iterable
     *
     * @return \Generator<TKey, list<TCollectionValue>|TCollectionValue|TReturnValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $item) {
            $result = $this->joinWith($item, $this->many);
            yield $key => null === $this->map ? $result : ($this->map)($item, $result);
        }
    }

    /**
     * @param TValueIterable $item
     *
     * @return ($many is true ? list<TCollectionValue> : TCollectionValue)
     */
    private function joinWith(mixed $item, bool $many): mixed
    {
        $filter = new Filter(
            callback: fn ($withItem) => ($this->on)($item, $withItem),
            collectNotFiltered: true
        );
        $matchCollection = new CoreCollection($this->collection, $filter);

        if (!$many) {
            return $matchCollection->first();
        }

        $result = $matchCollection->toList();
        $this->collection = new CoreCollection($filter->notFiltered());

        return $result;
    }
}
