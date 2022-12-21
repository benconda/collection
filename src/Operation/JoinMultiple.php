<?php

declare(strict_types=1);

namespace BenConda\Collection\Operation;

use Generator;

/**
 * @template TKey
 * @template TValue
 * @template TValueFrom
 *
 * @implements OperationInterface<TKey, TValue>
 */
final class JoinMultiple implements OperationInterface
{
    /**
     * @var Join\Config<mixed, mixed>[]
     */
    private array $config;

    /**
     *
     * @param Join\Config<TValueFrom, TValue> ...$config
     */
    public function __construct(Join\Config ...$config)
    {
        $this->config = $config;
    }

    /**
     * @param iterable<TKey, TValueFrom> $iterable
     *
     * @return Generator<TKey, array<TValue|TValueFrom>>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $key => $item) {
            $newItem = [$item];

            foreach ($this->config as $joinWith) {
                $newItem[] = ($joinWith)($item);
            }

            yield $key => $newItem;
        }
    }
}
