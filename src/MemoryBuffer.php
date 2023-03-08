<?php

declare(strict_types=1);

namespace BenConda\Collection;

use IteratorAggregate;
use Traversable;

/**
 * @template TKey
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
final class MemoryBuffer implements IteratorAggregate
{
    /** @var list<array{key: TKey, value: TValue}> */
    private array $buffer = [];
    private int $position = 0;

    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function buffer(mixed $key, mixed $value): void
    {
        $this->buffer[$this->position++] = ['key' => $key, 'value' => $value];
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function consume(): Traversable
    {
        while (null !== $buffer = array_shift($this->buffer)) {
            yield $buffer['key'] => $buffer['value'];
        }
    }

    public function getIterator(): Traversable
    {
        foreach ($this->buffer as $buffer) {
            yield $buffer['key'] => $buffer['value'];
        }
    }

    /**
     * @return Traversable<TKey, TValue>
     */
    public function reverseConsume(): Traversable
    {
        while (null !== $buffer = array_pop($this->buffer)) {
            yield $buffer['key'] => $buffer['value'];
        }
    }

    public function clearBuffer(): void
    {
        $this->buffer = [];
        $this->position = 0;
    }

    /**
     * @template TKeyNew
     * @template TValueNew
     *
     * @param iterable<TKeyNew, TValueNew> $iterable
     *
     * @return self<TKeyNew, TValueNew>
     */
    public static function bufferAll(iterable $iterable): self
    {
        /** @var self<TKeyNew, TValueNew> $self */
        $self = new self();
        foreach ($iterable as $key => $value) {
            $self->buffer($key, $value);
        }

        return $self;
    }

    public function isEmpty(): bool
    {
        return 0 === $this->position;
    }
}
