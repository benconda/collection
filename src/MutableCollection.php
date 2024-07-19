<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey
 * @template TValue
 *
 * @extends MutableCoreCollection<TKey, TValue>
 */
class MutableCollection extends MutableCoreCollection
{
    /** @use ModifiersTrait<TKey, TValue> */
    use ModifiersTrait;

    /**
     * @template TKeyModifier
     * @template TValueModifier
     *
     * @param ModifierInterface<TKeyModifier, TValueModifier> $modifier
     *
     * @return self<TKeyModifier, TValueModifier>
     */
    public function __invoke(ModifierInterface $modifier): self
    {
        $this->modify($modifier);

        return $this;
    }
}
