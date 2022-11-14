# Collection
Just another collection library, powered by generators, using static analysis, generics, easily extendable and immutable

# Requirement
To use this library you need at least php 8.0

# Usage

You first need to create the collection from an iterable, it can be an array, or anything that is iterable (generator, objects iterators, etc)

```PHP
$collection = Collection::from($myIterable);
```

Now you can apply one or multiple Operation to it : 
```PHP
use BenConda\Collection\Operation as Op;
// [...]

$collection = Collection::from(range(1, 10))
->apply(
  new Op\Filter(
    callback: fn(int $item): bool => $item > 5
  ),
)->apply(
  new Op\Map(
    callback: fn(int $item): string => "The number is $item"
  )
);

// Each time you call apply() it return a new instance of the collection, nothing is override !

// The collection is invokable, so you can add operations like this too : 

$collection = Collection::from(range(1, 10))
(
  new Op\Filter(
    callback: fn(int $item): bool => $item > 5
  ),
)
(
  new Op\Map(
    callback: fn(int $item): string => "The number is $item"
  )
);

// We can then get only the first value like this
$first = $collection->first(); // $first = The number is 5
// Note that it won't go through the whole array to return the first value, 
// behind the scene it only do 1 => 2 => 3 => 4 => 5 => The number is 5 and stop
// This is possible thanks to generators, most other Collection will need to loop through 
// the whole array to get the filtered version, then Map everything and to finish you get the first value using its offset

// We can iterate on all value using a foreach directly on the collection $object : 
foreach ($collection as $key => $item)
{
  // do what you want
}

// And you can translate the whole Collection into an array like this : 
iterator_to_array($collection);

// Be careful, by design we accept anything as a key, array restrict keys to int|string value so it can fail depending of your Collection TKey.
```
# Operations
There is some operations shipped with this library

## Join
This operation allow you to join a Collection with another Collection, using a match callback. The resulted Collection items are arrays containing the matched items, that you can then Map to create a new Class from it :

```mermaid
classDiagram
    class CarBrandCollection {
        Peugeot
        Citroen
    }
    class WheelCollection {
        CitroenWheelType1
        CitroenWheelType2
        PeugeotWheelGeneric
    }
    class Join {
        [Peugeot, [PeugeotWheelGeneric]]
        [Citroen, [CitroenWheelType1, CitroenWheelType2]]
        multiple(true)
    }
    CarBrandCollection --|> Join
    WheelCollection --|> Join
    class Car {
        string brandName
        array Wheels
    }
    class Map {
        Car[] cars
    }
    Join --|> Map
    Car <-- Map
```

# Extend

Coming soon
