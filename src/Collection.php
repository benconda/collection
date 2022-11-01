<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Operation\NullOperation;
use IteratorAggregate;

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
     * @template TKeyOp
     * @template TValueOp
     *
     * @param OperationInterface<TKeyOp, TValueOp> $operation
     *
     * @return self<TKeyOp, TValueOp, TKey, TValue>
     */
    public function op(OperationInterface $operation): self
    {
        return new self($this, $operation);
    }

    /**
     * @return \Generator<TKey, TValue>
     */
    public function getIterator()
    {
        yield from ($this->operation)($this->iterable);
    }

}