<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Collection;
use BenConda\Collection\Operation\Filter;
use BenConda\Collection\Operation\Flip;
use BenConda\Collection\Operation\JoinMultiple;
use BenConda\Collection\Operation\Join;
use BenConda\Collection\Operation\Map;
use PHPUnit\Framework\TestCase;

/**
 * @covers Collection
 */
final class CollectionTest extends TestCase
{

    public function testGetIterator(): void
    {
        $array = range(1, 10);
        $collection = Collection::from($array);
        $arrayResult = iterator_to_array($collection);
        $this->assertSame($array, $arrayResult);
    }

    /**
     * @covers Filter::
     */
    public function testWithOperation(): void
    {
        /** @var int[] $array */
        $array = range(1, 10);

        $collection = (Collection::from($array))
        (
            new Filter(
                filterCallback: fn(int $item) => 0 === $item % 2
            )
        );
        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([2, 4, 6, 8, 10], $arrayResult);
    }

    public function testMultipleOperation(): void
    {
        $array = range(1, 10);
        $collection = (Collection::from($array))
        (
            new Filter(
                filterCallback: fn(int $item) => 0 === $item % 2
            ),
        )
        (
            new Filter(
                filterCallback: fn(int $item) => $item > 6
            )
        );

        $arrayResult = iterator_to_array($collection, false);
        $this->assertSame([8, 10], $arrayResult);
    }

    /**
     * @covers Flip
     */
    public function testFlipOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->apply(new Flip());
        $arrayResult = iterator_to_array($collection);
        $this->assertSame(array_flip($array), $arrayResult);
    }

    /**
     * @covers Map
     */
    public function testMapOperation(): void
    {
        $array = range(1, 4);
        $collection = Collection::from($array)
            ->apply(new Map(
                fn(int $item): string => "The number is $item"
            ));

        $arrayResult = iterator_to_array($collection);
        $this->assertSame([
            'The number is 1',
            'The number is 2',
            'The number is 3',
            'The number is 4',
        ], $arrayResult);
    }

    /**
     * @return array<mixed>
     */
    public static function provideJoinData(): array
    {
        return [[
            Collection::from([
                [
                    'id' => '1',
                    'name' => 'Paris',
                ],
                [
                    'id' => '2',
                    'name' => 'Rennes'
                ]
            ]),
            Collection::from([
                [
                    'id' => '1',
                    'name' => 'Home',
                    'cityId' => '2'
                ],
                [
                    'id' => '2',
                    'name' => 'Work',
                    'cityId' => '1'
                ]
            ]),
            Collection::from([
                [
                    'id' => '1',
                    'name' => 'Daddy',
                    'positionId' => '1'
                ],
                [
                    'id' => '2',
                    'name' => 'Sister',
                    'positionId' => '1',
                ],
                [
                    'id' => '3',
                    'name' => 'Mother',
                    'positionId' => '2',
                ]
            ])
        ]];
    }

    /**
     * @dataProvider provideJoinData
     * @param Collection<int, array<string, string>, int, array<string, string>> $cityCollection
     * @param Collection<int, array<string, string>, int, array<string, string>> $localisationCollection
     * @param Collection<int, array<string, string>, int, array<string, string>> $personCollection
     */
    public function testJoinMultipleOperation(Collection $cityCollection, Collection $localisationCollection, Collection $personCollection): void
    {
        $joinLocalisationWithPersons = ($localisationCollection)(
            new JoinMultiple(
                new Join\Config(
                    collection: $personCollection,
                    on: fn(array $localisationItem, array $personItem) => $localisationItem['id'] === $personItem['positionId'],
                    multiple: true
                ),
                new Join\Config(
                    collection: $cityCollection,
                    on: fn(array $localisationItem, array $cityItem) => $localisationItem['cityId'] === $cityItem['id']
                )
            )
        );

        foreach ($joinLocalisationWithPersons as [$localisationItem, $persons, $cityItem]) {
            foreach ($persons as $person) {
                $this->assertSame($localisationItem['id'], $person['positionId']);
            }
            $this->assertSame($localisationItem['cityId'], $cityItem['id']);
        }
    }

    /**
     * @dataProvider provideJoinData
     * @param Collection<int, array<string, string>, int, array<string, string>> $cityCollection
     * @param Collection<int, array<string, string>, int, array<string, string>> $localisationCollection
     */
    public function testJoinOperation(Collection $cityCollection, Collection $localisationCollection): void
    {
        $joinLocalisationWithPersons = ($localisationCollection)(
            new Join(
                collection: $cityCollection,
                on: fn(array $localisationItem, array $cityItem) => $localisationItem['cityId'] === $cityItem['id']
            )
        );

        foreach ($joinLocalisationWithPersons as [$localisationItem, $cityItem]) {
            $this->assertSame($localisationItem['cityId'], $cityItem['id']);
        }
    }
}