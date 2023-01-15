<?php

declare(strict_types=1);

namespace BenConda\Collection\BufferModifier;

use BenConda\Collection\MemoryBuffer;
use BenConda\Collection\Modifier\ModifierInterface;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Reverse implements ModifierInterface
{
    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        $buffer = MemoryBuffer::bufferAll($iterable);

        while (null !== $element = array_pop($buffer)) {
            yield $element['key'] => $element['value'];
        }
    }
}
