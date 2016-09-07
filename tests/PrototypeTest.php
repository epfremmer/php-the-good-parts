<?php
/**
 * File PrototypeTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts\Tests;

use Closure;
use Epfremme\PHP\TheGoodParts\Prototype;
use Epfremme\PHP\TheGoodParts\Tests\Mocks;
use PHPUnit_Framework_TestCase;

/**
 * Class PrototypeTest
 *
 * @package Epfremme\PHP\TheGoodParts\Tests
 */
class PrototypeTest extends PHPUnit_Framework_TestCase
{
    /** @gourp prototype */
    public function testConstruct()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $this->assertAttributeSame($scope, 'scope', $prototype);
        $this->assertAttributeInternalType('array', 'methods', $prototype);
        $this->assertAttributeEmpty('methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetClosure()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $prototype->method = function () {};

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetCallable()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $prototype->method = 'Epfremme\PHP\TheGoodParts\Tests\Mocks\test_callable';

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetNativeCallable()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $prototype->method = 'array_sum';

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetMethodCallable()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);
        $testClass = new Mocks\TestClassWithMethod();

        $prototype->method = [$testClass, 'test'];

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicGet()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $self = $this;

        $prototype->method = function () use ($self, $scope) {
            $self->assertInstanceOf(Mocks\TestClass::class, $this);
            $self->assertSame($scope, $this);

            return true;
        };

        $this->assertInstanceOf(Closure::class, $prototype->method);
        $this->assertTrue(call_user_func($prototype->method));
    }

    /** @gourp prototype */
    public function testMagicGetWithCallable()
    {
        $scope = new Mocks\TestClassWithMethod();
        $prototype = new Prototype($scope);

        $prototype->method = [$scope, 'test'];

        $this->assertInstanceOf(Closure::class, $prototype->method);
        $this->assertSame($scope, call_user_func($prototype->method));
    }

    /** @gourp prototype */
    public function testMagicGetMissing()
    {
        $scope = new Mocks\TestClass();
        $prototype = new Prototype($scope);

        $this->assertNull($prototype->missing);
    }
}
