<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use BenConda\Collection\Modifier\Join;
use BenConda\Collection\Modifier\JoinMultiple;
use PHPUnit\Framework\TestCase;

/**
 * @phpstan-type CollectionOfAssocArray Collection<int, array<string, string>, int, array<string, string>>
 */
final class JoinTest extends TestCase
{
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
     * @covers JoinMultiple
     * @dataProvider provideJoinData
     * @param CollectionOfAssocArray $cityCollection
     * @param CollectionOfAssocArray $localisationCollection
     * @param CollectionOfAssocArray $personCollection
     */
    public function testJoinMultipleModifier(Collection $cityCollection, Collection $localisationCollection, Collection $personCollection): void
    {
        $joinLocalisationWithPersons = ($localisationCollection)(
            new JoinMultiple(
                new Join\Config(
                    collection: $personCollection,
                    on: fn (array $localisationItem, array $personItem) => $localisationItem['id'] === $personItem['positionId'],
                    many: true
                ),
                new Join\Config(
                    collection: $cityCollection,
                    on: fn (array $localisationItem, array $cityItem) => $localisationItem['cityId'] === $cityItem['id']
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
     * @covers Join
     * @dataProvider provideJoinData
     * @param CollectionOfAssocArray $cityCollection
     * @param CollectionOfAssocArray $localisationCollection
     */
    public function testJoinModifier(Collection $cityCollection, Collection $localisationCollection): void
    {
        $joinLocalisationWithPersons = ($localisationCollection)(
            new Join(
                collection: $cityCollection,
                on: fn (array $localisationItem, array $cityItem) => $localisationItem['cityId'] === $cityItem['id']
            )
        );

        foreach ($joinLocalisationWithPersons as [$localisationItem, $cityItem]) {
            $this->assertSame($localisationItem['cityId'], $cityItem['id']);
        }
    }
}
