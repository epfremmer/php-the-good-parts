<?php
/**
 * File Prototype.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts;

use Closure;

/**
 * Class Prototype
 *
 * @package Epfremme\PHP\TheGoodParts
 */
class Prototype
{
    /**
     * @var array
     */
    private $methods = [];

    /**
     * Set class prototype method
     *
     * @param string $name
     * @param callable $function
     */
    public function __set($name, callable $function)
    {
        if (!$function instanceof Closure) {
            $function = function() use ($function) {
                return call_user_func_array($function, func_get_args());
            };
        }

        $this->methods[$name] = $function;
    }

    /**
     * Get class prototype method
     *
     * @param string $name
     * @return null|Closure
     */
    public function __get($name)
    {
        return isset($this->methods[$name]) ? $this->methods[$name] : null;
    }
}
