<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\BufferedModifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Partition
 */
final class PartitionTest extends TestCase
{
    public function testPartition(): void
    {
        $taskList = [
            'key1' => new Task('Cleanup kitchen', State::ToDo),
            'key2' => new Task('Cleanup bathroom', State::ToDo),
            'key3' => new Task('Make breakfast', State::Doing),
            'key4' => new Task('Wake up', State::Done),
        ];
        $col = Collection::from($taskList)
            ->partition(static fn (Task $value) => $value->state);

        $allowedValues = array_map(
            static fn (State $enum): string => $enum->value,
            State::cases()
        );

        foreach ($col as $partitionKey => $value) {
            self::assertTrue(in_array($partitionKey, $allowedValues, true));
            foreach ($value as $key => $val) {
                self::assertSame($taskList[$key], $val);
            }
        }
    }
}

enum State: string
{
    case ToDo = 'todo';
    case Doing = 'doing';
    case Done = 'done';
}

final class Task
{
    public function __construct(
        public readonly string $name,
        public readonly State $state,
    ) {
    }
}
