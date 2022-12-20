<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Operation\NullOperation;
use BenConda\Collection\Operation\OperationInterface;
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
final class Collection implements IteratorAggregate
{

    /**
     *
     * @var iterable<TKeyIterable, TValueIterable>
     */
    private iterable $iterable;

    /**
     * @var OperationInterface<TKey, TValue>
     */
    private OperationInterface $operation;

    /**
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     * @param ?OperationInterface<TKey, TValue> $operation
     */
    private function __construct(iterable $iterable, ?OperationInterface $operation = null)
    {
        $this->iterable = $iterable;
        $this->operation = $operation ?? new NullOperation();
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
     * @param OperationInterface<TKeyOp, TValueOp> $operation
     *
     * @return self<TKeyOp, TValueOp, TKey, TValue>
     */
    public function apply(OperationInterface $operation): self
    {
        return new self($this, $operation);
    }

    /**
     * @template TKeyOp
     * @template TValueOp
     *
     * @param OperationInterface<TKeyOp, TValueOp> $operation
     *
     * @return self<TKeyOp, TValueOp, TKey, TValue>
     */
    public function __invoke(OperationInterface $operation): self
    {
        return $this->apply($operation);
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        yield from ($this->operation)($this->iterable);
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