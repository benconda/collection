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
     * @template TKeyModifier
     * @template TValueModifier
     *
     * @param ModifierInterface<TKeyModifier, TValueModifier> $modifier
     *
     * @return self<TKeyModifier, TValueModifier>
     */
    public function addModifier(ModifierInterface $modifier): self
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
