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
     * @var object
     */
    private $scope;

    /**
     * @var array
     */
    private $methods = [];

    /**
     * Proto constructor
     *
     * @param object $scope
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /**
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

        $this->methods[$name] = Closure::bind($function, $this->scope, get_class($this->scope));
    }

    /**
     * @param string $name
     * @return null|Closure
     */
    public function __get($name)
    {
        return isset($this->methods[$name]) ? $this->methods[$name] : null;
    }
}
