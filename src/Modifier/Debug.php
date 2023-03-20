<?php

declare(strict_types=1);

namespace BenConda\Collection\Modifier;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Debug implements ModifierInterface
{
    /** @var array<int, array{key: TKey, value: TValue}> */
    private array $log = [];

    /**
     * @param iterable<TKey, TValue> $iterable
     *
     * @return \Generator<TKey, TValue>
     */
    public function __invoke(iterable $iterable): \Generator
    {
        foreach ($iterable as $key => $value) {
            $this->log[] = ['key' => $key, 'value' => $value];
            yield $key => $value;
        }
    }

    /**
     * @return array<int, array{key: TKey, value: TValue}>
     */
    public function getDebugLog(): array
    {
        return $this->log;
    }
}
