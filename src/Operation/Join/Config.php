<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation\Join;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Filter;
use Closure;

/**
 * @template TValueFrom
 * @template TValue
 */
final class Config
{

    /**
     * @template TKey
     * @template TKeyIterable
     * @template TValueIterable
     *
     * @param Collection<TKey, TValue, TKeyIterable, TValueIterable> $collection
     * @param Closure(TValueFrom, TValue): bool $on
     */
    public function __construct(private Collection $collection, private Closure $on, private bool $multiple = false)
    {
    }

    /**
     * @param TValueFrom $item
     *
     * @return array<TValue>|TValue
     */
    public function __invoke($item)
    {
        $matchCollection = $this->collection->apply(
            new Filter(
                callback: fn($withItem) => ($this->on)($item, $withItem)
            )
        );

        $result = [];
        foreach ($matchCollection as $value) {
            if ($this->multiple) {
                $result[] = $value;
            } else {
                return $value;
            }
        }

        return $result;
    }

}