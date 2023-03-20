<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TValue, TKey>
 */
final class Flip implements ModifierInterface
{
    /**
     * @param iterable<TValue, TKey> $iterable
     *
     * @return \Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $value) {
            yield $value => $key;
        }
    }
}
