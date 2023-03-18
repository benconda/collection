<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\ModifierInterface;
use BenConda\Collection\Modifier\NullModifier;
use Generator;
use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class CoreCollection implements IteratorAggregate
{
    /**
     * @var ModifierInterface<TKey, TValue>
     */
    private readonly ModifierInterface $modifier;

    /**
     * @param iterable<mixed, mixed> $iterable
     * @param ModifierInterface<TKey, TValue> $modifier
     */
    public function __construct(
        private readonly iterable $iterable,
        ModifierInterface $modifier = null
    ) {
        if (null === $modifier) {
            /** @var NullModifier<TKey, TValue> $modifier */
            $modifier = new NullModifier();
        }

        $this->modifier = $modifier;
    }

    /**
     * @return Generator<TKey, TValue>
     */
    public function getIterator(): Generator
    {
        yield from ($this->modifier)($this->iterable);
    }

    /**
     * @return ?TValue
     */
    public function first(): mixed
    {
        foreach ($this as $item) {
            return $item;
        }

        return null;
    }

    public function execute(): void
    {
        foreach ($this as $item) {
            // Do nothing
        }
    }

    /**
     * @return array<int|TKey, TValue>
     */
    public function toArray(): array
    {
        $newArray = [];
        foreach ($this as $key => $item) {
            if (is_int($key) || is_string($key)) {
                $newArray[$key] = $item;
            } else {
                $newArray[] = $item;
            }
        }

        return $newArray;
    }

    /**
     * @return list<TValue>
     */
    public function toArrayList(): array
    {
        $newArray = [];
        foreach ($this as $item) {
            $newArray[] = $item;
        }

        return $newArray;
    }

    /**
     * @return ModifierInterface<TKey, TValue>
     */
    public function getModifier(): ModifierInterface
    {
        return $this->modifier;
    }
}
