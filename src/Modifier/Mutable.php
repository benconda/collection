<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Mutable implements ModifierInterface
{
    /**
     * @var array<int|string, ModifierInterface<mixed, mixed>>
     */
    private array $modifiers = [];

    /**
     * @param ModifierInterface<TKey, TValue> $modifier
     */
    public function addModifier(ModifierInterface $modifier): static
    {
        $this->modifiers[] = $modifier;

        return $this;
    }

    public function __invoke(iterable $iterable): \Generator
    {
        $currentIterable = $iterable;
        foreach ($this->modifiers as $modifier) {
            $currentIterable = $modifier($currentIterable);
        }

        yield from $currentIterable;
    }
}
