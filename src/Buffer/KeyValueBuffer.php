<?php

declare(strict_types=1);

namespace BenConda\Collection\Buffer;

/**
 * @template TKey
 * @template TValue
 */
final class KeyValueBuffer
{
    /**
     * @param TKey   $key
     * @param TValue $value
     */
    public function __construct(
        public mixed $key,
        public mixed $value
    ) {
    }
}
