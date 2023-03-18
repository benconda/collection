<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

use Generator;

/**
 * @template TKey
 * @template TValue of list<mixed>
 * @template TValueIterable
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Aggregate implements ModifierInterface
{
    /**
     * @var array<int|string, ModifierInterface<mixed, mixed>>
     */
    private array $modifiers;

    /**
     * @param ModifierInterface<mixed, mixed> ...$modifiers
     */
    public function __construct(ModifierInterface ...$modifiers)
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @param iterable<TKey, TValueIterable> $iterable
     *
     * @return Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        // Initialize all modifiers Generator
        $modifiersIterable = [];
        foreach ($this->modifiers as $modifier) {
            $modifiersIterable[] = ($modifier)($iterable);
        }

        foreach ($iterable as $key => $value) {
            $returnedValue = [$value];
            foreach ($modifiersIterable as $initializeModifier) {
                $returnedValue[] = $initializeModifier->current();
                $initializeModifier->next();
            }
            yield $key => $returnedValue;
        }
    }
}
