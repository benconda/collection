<?php

declare(strict_types=1);

namespace BenConda\Collection\BufferedModifier;

use BenConda\Collection\MemoryBuffer;
use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Cache implements ModifierInterface
{
    /** @var MemoryBuffer<TKey, TValue> */
    private MemoryBuffer $buffer;

    public function __construct()
    {
        $this->buffer = new MemoryBuffer();
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return \Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        if ($this->buffer->isEmpty()) {
            foreach ($iterable as $key => $value) {
                $this->buffer->buffer($key, $value);
                yield $key => $value;
            }

            return;
        }

        yield from $this->buffer;
    }
}
