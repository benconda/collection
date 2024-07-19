<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey
 * @template TValue
 *
 * @extends CoreCollection<TKey, TValue>
 */
final class Collection extends CoreCollection
{
    /** @use ModifiersTrait<TKey, TValue> */
    use ModifiersTrait;

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
}
