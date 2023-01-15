<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\NullModifier;
use BenConda\Collection\Modifier\ModifierInterface;
use IteratorAggregate;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @template TKeyIterable
 * @template TValueIterable
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class Collection implements IteratorAggregate
{
    /**
     *
     * @var iterable<TKeyIterable, TValueIterable>
     */
    private iterable $iterable;

    /**
     * @var ModifierInterface<TKey, TValue>
     */
    private ModifierInterface $modifier;

    /**
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     * @param ?ModifierInterface<TKey, TValue> $modifier
     */
    private function __construct(iterable $iterable, ?ModifierInterface $modifier = null)
    {
        $this->iterable = $iterable;
        $this->modifier = $modifier ?? new NullModifier();
    }

    /**
     *
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     * @return self<TKeyIterable, TValueIterable, TKeyIterable, TValueIterable>
     */
    public static function from(iterable $iterable): self
    {
        return new self($iterable);
    }

    /**
     * @return self<TKeyIterable, TValueIterable, TKeyIterable, TValueIterable>
     */
    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @template TKeyOp
     * @template TValueOp
     *
     * @param ModifierInterface<TKeyOp, TValueOp> $modifier
     *
     * @return self<TKeyOp, TValueOp, TKey, TValue>
     */
    public function apply(ModifierInterface $modifier): self
    {
        return new self($this, $modifier);
    }

    /**
     * @template TKeyOp
     * @template TValueOp
     *
     * @param ModifierInterface<TKeyOp, TValueOp> $operation
     *
     * @return self<TKeyOp, TValueOp, TKey, TValue>
     */
    public function __invoke(ModifierInterface $operation): self
    {
        return $this->apply($operation);
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
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
}
