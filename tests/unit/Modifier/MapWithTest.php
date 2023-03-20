<?php

declare(strict_types=1);

namespace BenCondaTest\Collection\Modifier;

use BenConda\Collection\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BenConda\Collection\Modifier\MapWith
 */
final class MapWithTest extends TestCase
{
    /** @var Collection<int, Item> */
    private Collection $itemCollection;
    /** @var Collection<int, Person> */
    private Collection $personCollection;
    /** @var Collection<int, Cart> */
    private Collection $cartCollection;

    protected function setUp(): void
    {
        $this->itemCollection = Collection::from([
            new Item(0, 'Banana'),
            new Item(1, 'Peach'),
            new Item(2, 'Peanuts'),
        ]);
        $this->personCollection = Collection::from([
            new Person(0, 'Person 1'),
            new Person(1, 'Person 2'),
            new Person(2, 'Person 3'),
        ]);
        $this->cartCollection = Collection::from([
            new Cart(personId: 0, itemId: 0),
            new Cart(personId: 0, itemId: 2),
            new Cart(personId: 0, itemId: 1),
            new Cart(personId: 1, itemId: 1),
        ]);
    }

    public function testMapWithModifier(): void
    {
        $personsWithCartItems = $this->personCollection
            ->mapWith(
                $this->cartCollection,
                on: fn (Person $person, Cart $cart): bool => $person->id === $cart->personId,
                map: fn (Person $person, array $cart): PersonWithCartItems => new PersonWithCartItems($person, Collection::from($cart)
                    ->mapWith($this->itemCollection, on: fn (Cart $cart, Item $item): bool => $cart->itemId === $item->id)->toList()),
                many: true
            );

        foreach ($personsWithCartItems as $item) {
            self::assertInstanceOf(PersonWithCartItems::class, $item);
        }
    }
}

class Item
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {
    }
}

class Person
{
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {
    }
}

class Cart
{
    public function __construct(
        public readonly int $personId,
        public readonly int $itemId
    ) {
    }
}

class PersonWithCartItems
{
    /**
     * @param array<Item> $cartItems
     */
    public function __construct(
        public readonly Person $person,
        public readonly array $cartItems
    ) {
    }
}
