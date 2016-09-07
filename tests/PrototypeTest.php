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
    public function testMagicSetClosure()
    {
        $prototype = new Prototype();

        $prototype->method = function () {};

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetCallable()
    {
        $prototype = new Prototype();

        $prototype->method = 'Epfremme\PHP\TheGoodParts\Tests\Mocks\test_callable';

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetNativeCallable()
    {
        $prototype = new Prototype();

        $prototype->method = 'array_sum';

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicSetMethodCallable()
    {
        $prototype = new Prototype();
        $testClass = new Mocks\TestClassWithMethod();

        $prototype->method = [$testClass, 'test'];

        $this->assertAttributeCount(1, 'methods', $prototype);
        $this->assertAttributeContainsOnly(Closure::class, 'methods', $prototype);
    }

    /** @gourp prototype */
    public function testMagicGet()
    {
        $prototype = new Prototype();

        $self = $this;

        $prototype->method = function () use ($self) {
            return true;
        };

        $this->assertInstanceOf(Closure::class, $prototype->method);
        $this->assertTrue(call_user_func($prototype->method));
    }

    /** @gourp prototype */
    public function testMagicGetWithCallable()
    {
        $prototype = new Prototype();
        $testClass = new Mocks\TestClassWithMethod();

        $prototype->method = [$testClass, 'test'];

        $this->assertInstanceOf(Closure::class, $prototype->method);
        $this->assertSame($testClass, call_user_func($prototype->method));
    }

    /** @gourp prototype */
    public function testMagicGetMissing()
    {
        $prototype = new Prototype();

        $this->assertNull($prototype->missing);
    }
}
