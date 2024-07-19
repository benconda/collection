<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Filter implements ModifierInterface
{
    /**
     * @param \Closure(TValue $item): bool $callback
     */
    public function __construct(
        private \Closure $callback,
    ) {
    }

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return \Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $item) {
            if (($this->callback)($item)) {
                yield $key => $item;
            }
        }
    }
}
