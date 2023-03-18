# Collection
Collection library, powered by generators, with generics, easily extendable and immutable. 
**Lazy** by design and memory friendly. This accept anything that is iterable (Generator, array, Iterator, ...)

# Requirement
To use this library you need at least php 8.1

# Usage

You first need to create the collection from an iterable, it can be an array, or anything that is iterable (generators, objects iterators, etc)

```PHP
$collection = Collection::from($myIterable);
```

Now you can apply one or multiple Modifier to it :

```PHP
use BenConda\Collection\Collection;
// [...]

$collection = Collection::from(range(1, 10))
    ->filter(callback: static fn(int $item): bool => $item > 5)
    ->map(on: fn(int $item): string => "The number is $item");
```
Each time you call a modifier method it returns a new instance of the collection, nothing is override.

The collection is invokable, so you can add modifiers like this too : 
```PHP
$collection = Collection::from(range(1, 10))
(
  new Filter(
    callback: fn(int $item): bool => $item >= 5
  )
)
(
  new Map(
    callback: fn(int $item): string => "The number is $item"
  )
);
```
Because each modifier rely on generators, nothing is done until you start to iterate on the collection.


You can get the first value like this :
```PHP
$first = $collection->first(); // $first = The number is 5
```
Note that it won't go through the whole array to return the first value, 
behind the scene it only iterate through 1,2,3,4,5 => `The number is 5` and stop.

This is possible thanks to generators, most other Collection libraries will need to loop through 
the whole array to filter it, then Map every filtered value before you can get the first value.

We can iterate on all value using a foreach directly on the collection $object :
```PHP
$collection = Collection::from(range(1, 10))
foreach ($collection as $key => $item)
{
  // do what you want
}
```
And you can translate the whole Collection into an array like this : 
```PHP
$collection->toArray();
```
Be careful, by design we accept anything as a key, array restrict keys to `int|string` type, if the key type mismatch, it will fallback to int incremented key.

# Modifiers

Collection iteration can be altered using modifiers, which allow you to shape and transform data in a memory friendly way.

Some modifiers require some memory buffering, and are split in another namespace called `BufferedModifier`. This is the case for example with `BenConda\Collection\BufferedModifier\Reverse`, in order to reverse, the whole collection need to be loaded in memory.

So keep in mind, `BufferedModifier` namespace = will consume memory depending on the size of the iterable.

You will find the modifiers list [in this documentation](./docs/modifiers.md)


Note : documentation is in progress
# Extend

## Add custom modifier
Simply create a class that implement `BenConda\Collection\Modifier\ModifierInterface`

For example, for the reindex modifier :

```PHP
use Generator;
use BenConda\Collection\Modifier\ModifierInterface;

/**
 * @template TKey
 * @template TValue
 *
 * @implements ModifierInterface<TKey, TValue>
 */
final class Reindex implements ModifierInterface
{
    /**
     *
     * @param iterable<TKey, TValue> $iterable
     *
     * @return Generator<int, TValue>
     */
    public function __invoke(iterable $iterable): Generator
    {
        foreach ($iterable as $value) {
            yield $value;
        }
    }
}
```

If you need some configuration, simply add a constructor to the class.

## Using your own collection class
Sometimes you need to create your own, strict typed Collection class.

To do so, you can extend the CoreCollection class, and implement only the needed modifier method (or use the invoke style)
