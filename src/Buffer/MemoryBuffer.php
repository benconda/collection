<?php

declare(strict_types=1);

namespace BenConda\Collection\Buffer;

use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 *
 * @implements IteratorAggregate<TKey, TValue>
 * @implements \ArrayAccess<TKey, TValue>
 */
final class MemoryBuffer implements \IteratorAggregate, \ArrayAccess
{
    /** @var list<KeyValueBuffer<TKey, TValue>> */
    private array $buffer = [];
    private int $position = 0;

    /**
     * @param TKey   $key
     * @param TValue $value
     */
    public function buffer(mixed $key, mixed $value): void
    {
        $this->buffer[$this->position++] = new KeyValueBuffer($key, $value);
    }

    /**
     * @return \Traversable<TKey, TValue>
     */
    public function consume(): \Traversable
    {
        while (null !== $buffer = array_shift($this->buffer)) {
            yield $buffer->key => $buffer->value;
        }
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->buffer as $buffer) {
            yield $buffer->key => $buffer->value;
        }
    }

    /**
     * @return \Traversable<TKey, TValue>
     */
    public function reverseConsume(): \Traversable
    {
        while (null !== $buffer = array_pop($this->buffer)) {
            yield $buffer->key => $buffer->value;
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

    public function offsetExists(mixed $offset): bool
    {
        foreach ($this->buffer as $buffer) {
            if ($offset === $buffer->key) {
                return true;
            }
        }

        return false;
    }

    public function offsetGet(mixed $offset): mixed
    {
        foreach ($this->buffer as $buffer) {
            if ($offset === $buffer->key) {
                return $buffer->value;
            }
        }

        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (null === $offset) {
            return;
        }

        foreach ($this->buffer as $buffer) {
            if ($offset === $buffer->key) {
                $buffer->value = $value;

                return;
            }
        }

        $this->buffer($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        foreach ($this->buffer as $key => $buffer) {
            if ($offset === $buffer->key) {
                unset($this->buffer[$key]);

                return;
            }
        }
    }

    public static function toArrayKey(mixed $key): string|int|null
    {
        if (is_int($key) || is_string($key)) {
            return $key;
        } elseif ($key instanceof \Stringable) {
            return $key->__toString();
        } elseif ($key instanceof \BackedEnum) {
            return $key->value;
        }

        return null;
    }
}
