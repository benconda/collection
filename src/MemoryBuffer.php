<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey
 * @template TValue
 */
final class MemoryBuffer
{
    /** @var list<array{key: TKey, value: TValue}>  */
    private array $buffer = [];
    private int $position = 0;
    /** @var \Iterator<TKey, TValue> */
    private \Iterator $generator;

    /**
     * @param iterable<TKey, TValue> $iterable
     */
    public function __construct(
        iterable $iterable
    ) {
        $this->generator = $this->iterableToGenerator($iterable);
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return \Iterator<TKey, TValue>
     */
    private function iterableToGenerator(iterable $iterable): \Iterator
    {
        yield from $iterable;
    }

    /**
     * @return list<array{key: TKey, value: TValue}>
     */
    public function getBuffer(int $untilPosition = null): array
    {
        while ((null === $untilPosition || $this->position < $untilPosition) && $this->generator->valid()) {
            $this->buffer[$this->position++] = ['key' => $this->generator->key(), 'value' => $this->generator->current()];
            $this->generator->next();
        }

        return $this->buffer;
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     * @return list<array{key: TKey, value: TValue}>
     */
    public static function bufferAll(iterable $iterable): array
    {
        return (new self($iterable))->getBuffer();
    }
}
