<?php
/**
 * File classes.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
namespace Epfremme\PHP\TheGoodParts\Tests\Mocks;

use Epfremme\PHP\TheGoodParts\Mixin\Javascript;

class TestClass {}

class TestPrototype {
    use Javascript;
}

class TestClassWithMethod {
    public function test() { return $this; }
}

class TestPrototypeWithProperty extends TestPrototype {
    public $property = 'prop';
}

class TestPrototypeWithMethod extends TestClassWithMethod {
    use Javascript;
}

class TestPrototypeWithParent extends TestPrototype {
    use Javascript;
}

function test_callable() {}
