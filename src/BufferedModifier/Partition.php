<?php

declare(strict_types=1);

namespace BenConda\Collection\BufferedModifier;

use BenConda\Collection\Buffer\MemoryBuffer;
use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey of array-key
 * @template TKeyIterable
 * @template TValueIterable
 *
 * @implements ModifierInterface<TKey, iterable<TKeyIterable, TValueIterable>>
 */
final class Partition implements ModifierInterface
{
    /**
     * @param \Closure(TValueIterable, TKeyIterable): (TKey|\Stringable|\BackedEnum) $on
     */
    public function __construct(private readonly \Closure $on)
    {
    }

    /**
     * @param iterable<TKeyIterable, TValueIterable> $iterable
     *
     * @return \Generator<TKey, iterable<TKeyIterable, TValueIterable>>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        /** @var array<TKey, MemoryBuffer<TKeyIterable, TValueIterable>> $partition */
        $partition = [];
        foreach ($iterable as $key => $value) {
            $partitionKey = ($this->on)($value, $key);
            $partitionKey = MemoryBuffer::toArrayKey($partitionKey);
            if (null === $partitionKey) {
                throw new \LogicException('Provided partition callback must return a string|int, stringable or backed enum value');
            }
            $partition[$partitionKey] ??= new MemoryBuffer();
            $partition[$partitionKey]->buffer($key, $value);
        }

        foreach ($partition as $key => $value) {
            yield $key => $value->getIterator();
        }
    }
}
