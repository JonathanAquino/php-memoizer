<?php
/**
 * Caches expensive method calls. To memoize $object->foo(), add "use Memoize;"
 * to the class, then define a protected method called memoizedFoo() or
 * memoizedStaticFoo(). The former will do instance-level caching; the latter,
 * class-level caching. Special consideration must be given to classes that
 * already define __call().
 *
 * This code is based on http://rcontrerask.wordpress.com/2013/01/09/abstract-memoizable-class-for-php/
 * and http://www.blainesch.com/418/traits-memoize/ .
 */
trait Memoize {

    /** Store per instance values. */
    protected $instanceValues = array();

    /** Store per instance values. */
    protected static $staticValues = array();

    /** @Override */
    public function __call($methodname, $args) {
        array_walk_recursive($args, function($arg) {
            if (is_object($arg)) {
                throw new \Exception('Memoize does not support object arguments: ' . get_class($arg));
            }
        });
        $memoizedmethod = 'memoizedStatic' . $methodname;
        if (method_exists($this, $memoizedmethod)) {
            $key = $methodname . '-' . json_encode($args);
            if (isset(self::$staticValues[$key])) {
                return self::$staticValues[$key];
            }
            return self::$staticValues[$key] = call_user_func_array(array(
                $this, $memoizedmethod) , $args);
        }
        $memoizedmethod = 'memoized' . $methodname;
        if (method_exists($this, $memoizedmethod)) {
            $key = $methodname . '-' . json_encode($args);
            if (isset($this->instanceValues[$key])) return $this->instanceValues[$key];
            return $this->instanceValues[$key] = call_user_func_array(array(
                $this, $memoizedmethod ) , $args);
        }
        throw new \Exception('Method ' . get_class($this) . "->$methodname() does not exist.");
    }

}