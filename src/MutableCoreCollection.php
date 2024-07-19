<?php

declare(strict_types=1);

namespace BenConda\Collection;

use BenConda\Collection\Modifier\ModifierInterface;
use BenConda\Collection\Modifier\Mutable;

/**
 * @template TKey
 * @template TValue
 *
 * @extends CoreCollection<TKey, TValue>
 */
class MutableCoreCollection extends CoreCollection
{
    /** @var Mutable<TKey, TValue> */
    private Mutable $innerMutable;

    public function __construct(iterable $iterable)
    {
        parent::__construct($iterable, $this->innerMutable = new Mutable());
    }

    /**
     * @template TKeyModifier
     * @template TValueModifier
     *
     * @param ModifierInterface<TKeyModifier, TValueModifier> $modifier
     */
    protected function modify(ModifierInterface $modifier): void
    {
        $this->innerMutable->addModifier($modifier);
    }
}
