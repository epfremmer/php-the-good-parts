<?php
/**
 * File Javascript.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts\Mixin;

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
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments = [])
    {
        $prototype = $this->prototype();

        if (is_callable($prototype->$name)) {
            return call_user_func_array($prototype->$name->bindTo($this), $arguments);
        }

        $class = get_class();
        while ($parent = get_parent_class($class)) {
            if (method_exists($parent, 'prototype')) {
                $prototype = $parent::prototype();

                if (is_callable($prototype->$name)) {
                    return call_user_func_array($prototype->$name->bindTo($this), $arguments);
                }
            }

            $class = $parent;
        }

        throw new \BadMethodCallException;
    }

    /**
     * @param string $name
     * @return Prototype|mixed
     */
    public function __get($name)
    {
        return PROTO_PROPERTY === $name ? $this->prototype() : $this->$name;
    }

    /**
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
