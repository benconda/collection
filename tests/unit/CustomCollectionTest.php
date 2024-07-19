<?php

declare(strict_types=1);

namespace BenCondaTest\Collection;

use BenConda\Collection\Modifier\Add;
use BenConda\Collection\Modifier\Filter;
use BenConda\Collection\MutableCollection;
use BenConda\Collection\MutableCoreCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CoreCollection
 */
final class CustomCollectionTest extends TestCase
{
    /**
     * @var list<Car>
     */
    private array $basedCarList;
    private CarCollection $carCollection;

    /** @var MutableCollection<int, Car> */
    private MutableCollection $mutableCollection;

    protected function setUp(): void
    {
        $this->basedCarList = [
            new Car('5861235d-5e97-4d8f-86fa-2d0526f8cf37', 'C3', 'Citroën'),
            new Car('79eb018e-ea64-49b3-b827-0c205ae2ced1', 'C4 cactus', 'Citroën'),
            new Car('c72392e7-42ad-45e0-8c7b-a9ab9ffe9b38', '208', 'Peugeot'),
        ];

        $this->carCollection = new CarCollection($this->basedCarList);
        $this->mutableCollection = new MutableCollection($this->basedCarList);
    }

    public function testCustomCollection(): void
    {
        self::assertSame($this->basedCarList, $this->carCollection->toArray());
        self::assertSame($this->basedCarList, $this->mutableCollection->toArray());
    }

    public function testGetFirst(): void
    {
        self::assertSame($this->basedCarList[0], $this->carCollection->first());
        self::assertSame($this->basedCarList[0], $this->mutableCollection->first());
    }

    public function testAddItem(): void
    {
        $this->carCollection->add($newCar = new Car('5393afe4-a62e-4455-9423-c32b6183e563', '308', 'Peugeot'));
        $this->mutableCollection->add([$newCar]);
        $expectedList = [...$this->basedCarList, $newCar];
        self::assertSame($expectedList, $this->carCollection->toList());
        self::assertSame($expectedList, $this->mutableCollection->toList());
    }

    public function testRemoveItem(): void
    {
        $baseCarList = $this->basedCarList;
        $removedItem = $baseCarList[1];
        $this->carCollection->remove($removedItem);
        $this->mutableCollection->filter(callback: static fn (Car $carItem) => $carItem !== $removedItem);
        unset($baseCarList[1]);
        self::assertSame($baseCarList, $this->carCollection->toArray());
        self::assertSame($baseCarList, $this->mutableCollection->toArray());
    }
}

final class Car
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $brand
    ) {
    }
}

/**
 * @extends MutableCoreCollection<int, Car>
 */
final class CarCollection extends MutableCoreCollection
{
    public function add(Car $car): self
    {
        $this->modify(new Add([$car]));

        return $this;
    }

    public function remove(Car $car): self
    {
        $this->modify(new Filter(callback: static fn (Car $carItem) => $carItem !== $car));

        return $this;
    }
}
