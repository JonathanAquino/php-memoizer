php-memoizer
============

A PHP trait that can be used to add automatic memoization to a class. Based on
http://rcontrerask.wordpress.com/2013/01/09/abstract-memoizable-class-for-php/ .

To use it, first add "use Memoizer;" to your class. Then name your method
memoizedFoo() for instance-level caching, or memoizedStaticFoo() for class-level
caching. Then when you call $object->foo(), the result will be memoized.

Example:

    class Foo {
        use Memoize;
        protected function memoizedBar($x) {
            var_dump('Running bar for ' . $x);
            return $x;
        }
        protected function memoizedStaticQux($x) {
            var_dump('Running qux for ' . $x);
            return $x;
        }
    }

    $foo = new Foo;
    var_dump($foo->bar(5));
    var_dump($foo->bar(5));
    $foo = new Foo;
    var_dump($foo->bar(5));
    var_dump($foo->bar(5));
    $foo = new Foo;
    var_dump($foo->qux(5));
    var_dump($foo->qux(5));
    $foo = new Foo;
    var_dump($foo->qux(5));
    var_dump($foo->qux(5));

This outputs:

    string(17) "Running bar for 5"
    int(5)
    int(5)
    string(17) "Running bar for 5"
    int(5)
    int(5)
    string(17) "Running qux for 5"
    int(5)
    int(5)
    int(5)
    int(5)

