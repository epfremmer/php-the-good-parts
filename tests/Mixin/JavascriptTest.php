<?php
/**
 * File JavascriptTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts\Tests\Mixin;

use BadMethodCallException;
use Closure;
use Epfremme\PHP\TheGoodParts\Prototype;
use Epfremme\PHP\TheGoodParts\Tests\Mocks;
use PHPUnit_Framework_TestCase;

/**
 * Class JavascriptTest
 *
 * @package Epfremme\PHP\TheGoodParts\Tests\Mixin
 */
class JavascriptTest extends PHPUnit_Framework_TestCase
{
    /** @group javascript */
    public function testPrototype()
    {
        $mock = new Mocks\TestPrototype();

        $this->assertInstanceOf(Prototype::class, $mock->prototype());
    }

    /** @group javascript */
    public function testStaticPrototype()
    {
        $this->assertInstanceOf(Prototype::class, Mocks\TestPrototype::prototype());
    }

    /** @group javascript */
    public function testMagicGet()
    {
        $mock = new Mocks\TestPrototype();

        $this->assertInstanceOf(Prototype::class, $mock->prototype);
    }

    /** @group javascript */
    public function testMagicCall()
    {
        $mock = new Mocks\TestPrototype();
        $self = $this;

        $mock->prototype()->method = function () use ($self, $mock) {
            $self->assertInstanceOf(Mocks\TestPrototype::class, $this);
            $self->assertSame($mock, $this);

            return true;
        };

        $this->assertInstanceOf(Closure::class, $mock->prototype()->method);
        $this->assertTrue($mock->method());
    }

    /** @group javascript */
    public function testMagicCallExistingMethod()
    {
        $mock = new Mocks\TestPrototypeWithMethod();
        $self = $this;

        $mock->prototype()->method = function () use ($self) {
            $self->fail('prototype method called unexpectedly');
        };

        $mock->test();
    }

    /** @group javascript */
    public function testMagicCallBoundToInstance()
    {
        $mock1 = new Mocks\TestPrototype();
        $self = $this;

        $mock1->prototype()->method = function () use ($self, &$mock2) {
            $self->assertInstanceOf(Mocks\TestPrototype::class, $this);
            $self->assertSame($mock2, $this);

            return true;
        };

        $mock2 = new Mocks\TestPrototype();

        $this->assertInstanceOf(Closure::class, $mock2->prototype()->method);
        $this->assertTrue($mock2->method());
    }

    /** @group javascript */
    public function testMagicCallMissingMethod()
    {
        $mock = new Mocks\TestPrototype();

        $this->expectException(BadMethodCallException::class);
        $mock->missing();
    }

    /** @group javascript */
    public function testMagicCallMissingMethodWithPrototypeChain()
    {
        $mock = new Mocks\TestPrototypeWithParent();

        $this->expectException(BadMethodCallException::class);
        $mock->missing();
    }

    /** @group javascript */
    public function testMagicCallWithPrototypeChain()
    {
        $parent = new Mocks\TestPrototype();
        $parent->prototype()->parent_method = function () {
            return true;
        };

        $mock = new Mocks\TestPrototypeWithParent();
        $mock->prototype()->child_method = function () {
            return 1;
        };

        $this->assertEquals(1, $mock->child_method());
        $this->assertTrue($mock->parent_method());
    }
}
