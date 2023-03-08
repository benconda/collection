<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use Generator;

/**
 * @template TKey
 * @template-covariant TValue
 */
interface ModifierInterface
{
    /**
     * @template TKeyIterable
     * @template TValueIterable
     *
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator;
}
