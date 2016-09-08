<?php
/**
 * File Javascript.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts\Mixin;

use Closure;
use Epfremme\PHP\TheGoodParts\Prototype;

const PROTO_PROPERTY = 'prototype';

/**
 * Class Javascript
 *
 * @package Epfremme\PHP\TheGoodParts\Mixin
 */
trait Javascript
{
    /**
     * @var Prototype
     */
    private static $prototype;

    /**
     * Call missing concrete class method
     *
     * Used to search the prototype chain for a defined prototype method
     * and invoke it as the class instance
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments = [])
    {
        $prototype = $this->prototype();

        if (is_callable($prototype->$name)) {
            return $this->call($prototype->$name, $arguments);
        }

        $parent= get_class();
        while ($parent = get_parent_class($parent)) {
            if (method_exists($parent, 'prototype') && is_callable($parent::prototype()->$name)) {
                return $this->call($parent::prototype()->$name, $arguments);
            }
        }

        throw new \BadMethodCallException();
    }

    /**
     * Bind and call closure in the context/visibility of the current instance
     *
     * @param Closure $closure
     * @param array $arguments
     * @return mixed
     */
    private function call(Closure $closure, array $arguments = [])
    {
        return call_user_func_array($closure->bindTo($this, get_class($this)), $arguments);
    }

    /**
     * Return class or prototype properties
     *
     * @param string $name
     * @return Prototype|mixed
     */
    public function __get($name)
    {
        if (PROTO_PROPERTY === $name) {
            return $this->prototype();
        }

        return isset($this->$name) ? $this->$name : $this->prototype()->$name;
    }

    /**
     * Return new or current class prototype
     *
     * @return Prototype
     */
    public static function prototype()
    {
        if (null === self::$prototype) {
            self::$prototype = new Prototype();
        }

        return self::$prototype;
    }
}
