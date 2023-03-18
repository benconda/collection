<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use BenConda\Collection\MemoryBuffer;
use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Filter implements ModifierInterface
{
    /**
     * @var MemoryBuffer<TKey, TValue>
     */
    private MemoryBuffer $memoryBuffer;

    /**
     * @param Closure(TValue $item): bool $callback
     */
    public function __construct(
        private Closure $callback,
        private bool $multiple = true,
        private bool $collectNotFiltered = false
    ) {
        $this->memoryBuffer = new MemoryBuffer();
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        $this->memoryBuffer->clearBuffer();

        foreach ($iterable as $key => $item) {
            if (($this->callback)($item)) {
                yield $key => $item;
                if (false === $this->multiple) {
                    return;
                }
            } elseif ($this->collectNotFiltered) {
                $this->memoryBuffer->buffer($key, $item);
            }
        }
    }

    /**
     * @return iterable<TKey, TValue>
     */
    public function notFiltered(): iterable
    {
        yield from $this->memoryBuffer;
    }
}
