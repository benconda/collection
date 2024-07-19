<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\BufferedModifier\Cache;
use BenConda\Collection\BufferedModifier\Partition;
use BenConda\Collection\BufferedModifier\Reverse;
use BenConda\Collection\Modifier\Add;
use BenConda\Collection\Modifier\Aggregate;
use BenConda\Collection\Modifier\Each;
use BenConda\Collection\Modifier\Filter;
use BenConda\Collection\Modifier\Flip;
use BenConda\Collection\Modifier\Join;
use BenConda\Collection\Modifier\Map;
use BenConda\Collection\Modifier\ModifierInterface;
use BenConda\Collection\Modifier\Reindex;

/**
 * @template TKey
 * @template TValue
 */
trait ModifiersTrait
{
    /**
     * @param iterable<TKey, TValue> $items
     *
     * @return self<TKey, TValue>
     */
    public function add(iterable $items): self
    {
        return ($this)(new Add(items: $items));
    }

    /**
     * @param ModifierInterface<mixed, mixed> ...$modifiers
     *
     * @return self<TKey, list<mixed>>
     */
    public function aggregate(ModifierInterface ...$modifiers): self
    {
        return ($this)(new Aggregate(...$modifiers));
    }

    /**
     * @return self<TKey, TValue>
     */
    public function cache(): self
    {
        return ($this)(new Cache());
    }

    /**
     * @param \Closure(TValue): void $callback
     *
     * @return self<TKey, TValue>
     */
    public function each(\Closure $callback): self
    {
        return ($this)(new Each(callback: $callback));
    }

    /**
     * @param \Closure(TValue $item): bool $callback
     *
     * @return self<TKey, TValue>
     */
    public function filter(\Closure $callback): self
    {
        return ($this)(new Filter(callback: $callback));
    }

    /**
     * @return self<TValue, TKey>
     */
    public function flip(): self
    {
        /** @var Flip<TKey, TValue> $modifier */
        $modifier = new Flip();

        return ($this)($modifier);
    }

    /**
     * @template TJoinValue
     * @template TJoinKey
     * @template TReturnValue
     *
     * @param iterable<TJoinKey, TJoinValue>                                    $collection
     * @param \Closure(TValue, TJoinValue): bool                                $on
     * @param \Closure(TValue, Collection<TJoinKey, TJoinValue>) : TReturnValue $select
     *
     * @return self<TKey, TReturnValue>
     */
    public function join(iterable $collection, \Closure $on, \Closure $select): self
    {
        return ($this)(new Join(
            collection: $collection,
            on: $on,
            select: $select
        ));
    }

    /**
     * @template TModifierValue
     *
     * @param \Closure(TValue, TKey): TModifierValue $on
     *
     * @return self<TKey, TModifierValue>
     */
    public function map(\Closure $on): self
    {
        return ($this)(new Map(on: $on));
    }

    /**
     * @return self<int, TValue>
     */
    public function reindex(): self
    {
        /** @var Reindex<TKey, TValue> $modifier */
        $modifier = new Reindex();

        return ($this)($modifier);
    }

    /**
     * @return self<TKey, TValue>
     */
    public function reverse(): self
    {
        /** @var Reverse<TKey, TValue> $modifier */
        $modifier = new Reverse();

        return ($this)($modifier);
    }

    /**
     * @param \Closure(TValue, TKey):(array-key|\Stringable|\BackedEnum) $on
     *
     * @return self<array-key, iterable<TKey, TValue>>
     */
    public function partition(\Closure $on): self
    {
        /** @var Partition<array-key, TKey, TValue> $modifier */
        $modifier = new Partition($on);

        return ($this)($modifier);
    }

    /**
     * @template TKeyModifier
     * @template TValueModifier
     *
     * @param ModifierInterface<TKeyModifier, TValueModifier> $modifier
     *
     * @return self<TKeyModifier, TValueModifier>
     */
    abstract public function __invoke(ModifierInterface $modifier): self;
}
