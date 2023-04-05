# Modifiers

## Add
Add items in a collection, from another iterable.
Keys from added iterator are kept, you can combine with `Reindex` modifier to overcome this.

[Example in test](../tests/unit/Modifier/AddTest.php)

## Aggregate
Aggregate is a powerful modifier, that allow you to aggregate multiple modifier and iterate through them asynchronously.

It can help to construct Domain Driven Design aggregates, combined with MapWith modifier.

[Example in test](../tests/unit/Modifier/AggregateTest.php)

## Debug
A special modifier to test or dump each key / value that go through.

## Each
Allow you to do stuff on each key / value. The provided callback have to return nothing.

[Example in test](../tests/unit/Modifier/EachTest.php)

## Filter
Filter collection if provided callback return value is falsy. (Return true on element you want to keep)

## Flip
Same as array_flip

[Example in test](../tests/unit/Modifier/FlipTest.php)

## Map
Allow you to change the value with the callback return value.

[Example in test](../tests/unit/Modifier/MapTest.php)

## MapWith
A powerful modifier, that will map each item, with items from another collection.

The on attribute is a Closure used to select items that match (must return a boolean value like filter modifier)

The map attribute allow you to provide a closure to alter the value that will be used for the new Collection value

Many attribute allow you to specify if each item will map with a single value, or multiple (array).

[Example in test](../tests/unit/Modifier/MapWithTest.php)

## Reindex
This modifier will replace all the keys with a standard array index (starting from 0)

[Example in test](../tests/unit/Modifier/MapTest.php)

# Buffered Modifiers

## Cache
This modifier will keep a cache in memory, it's useful if you need to loop multiple time in your 
collection and you want to avoid previous modifiers to be called each time 
OR if you pass a Generator as a collection input, you can't loop more than one times, using cache allow you to loop multiple times.

## Reverse
This modifier will reverse items orders. To do that, it will need to load the provided iterable entirely in memory.

[Example in test](../tests/unit/BufferedModifier/ReverseTest.php)

## Partition
Partition using the provided closure returned value as partitioning key. 
Can be useful to put item with same category together.

[Example in test](../tests/unit/BufferedModifier/PartitionTest.php)
