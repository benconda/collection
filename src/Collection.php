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
use BenConda\Collection\Modifier\Map;
use BenConda\Collection\Modifier\JoinWith;
use BenConda\Collection\Modifier\ModifierInterface;
use BenConda\Collection\Modifier\Reindex;
use Closure;

/**
 * @template TKey
 * @template TValue
 *
 * @extends CoreCollection<TKey, TValue>
 */
final class Collection extends CoreCollection
{
    /**
     * @template TIterableKey
     * @template TIterableValue
     *
     * @param iterable<TIterableKey, TIterableValue> $iterable
     * @param ?ModifierInterface<TKey, TValue>       $modifier
     */
    private function __construct(iterable $iterable, ?ModifierInterface $modifier = null)
    {
        parent::__construct($iterable, $modifier);
    }

    /**
     * @template TFromKey
     * @template TFromValue
     *
     * @param iterable<TFromKey, TFromValue> $iterable
     *
     * @return self<TFromKey, TFromValue>
     */
    public static function from(iterable $iterable): self
    {
        return new self($iterable);
    }

    /**
     * @return self<mixed, mixed>
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @template TKeyModifier
     * @template TValueModifier
     *
     * @param ModifierInterface<TKeyModifier, TValueModifier> $modifier
     *
     * @return self<TKeyModifier, TValueModifier>
     */
    public function __invoke(ModifierInterface $modifier): self
    {
        return new self($this, $modifier);
    }

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
     * @return self<TKey, TValue>
     */
    public function cache(): self
    {
        return ($this)(new Cache());
    }

    /**
     * @param Closure(TValue $item): bool $callback
     *
     * @return self<TKey, TValue>
     */
    public function filter(\Closure $callback): self
    {
        return ($this)(new Filter(callback: $callback));
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
     * @template TJoinValue
     * @template TJoinKey
     * @template TReturnValue
     *
     * @param CoreCollection<TJoinKey, TJoinValue> $collection
     * @param \Closure(TValue, TJoinValue): bool   $on
     * @param ?Closure(TValue, ($many is true ? list<TJoinValue> : TJoinValue)):TReturnValue $map
     *
     * @return self<TKey, ($map is null ? ($many is true ? list<TJoinValue> : TJoinValue) : list<TJoinValue>|TJoinValue|TReturnValue)>
     */
    public function joinWith(CoreCollection $collection, \Closure $on, \Closure $map = null, bool $many = false, bool $innerJoin = false): self
    {
        return ($this)(new JoinWith(
            collection: $collection,
            on: $on,
            map: $map,
            many: $many,
            innerJoin: $innerJoin
        ));
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
     * @param \Closure(TValue): void $callback
     *
     * @return self<TKey, TValue>
     */
    public function each(\Closure $callback): self
    {
        return ($this)(new Each(callback: $callback));
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
}
