# PHP The Good Parts

[![Build Status](https://travis-ci.org/epfremmer/php-the-good-parts.svg?branch=master)](https://travis-ci.org/epfremmer/php-the-good-parts)

Make PHP JavaScript again

## Overview

Adds support for JavaScript like prototypes on PHP classes. Now you can define prototypical methods on your PHP classes
just like the magic of JavaScript. No more concrete class definitions! Now you can simply define that at runtime, or better
yet, dynamically!

## Installation

With Composer

```bash
composer config repositories.epfremme/php-the-good-parts vcs https://github.com/epfremmer/php-the-good-parts.git
composer require epfremme/php-the-good-parts
```

## Examples

### Basic Usage

```php
class FooClass
{
    use Javascript;
}

FooClass::prototype()->my_method = function() {
    echo 'Hello JavaScript!!!';
    
    return $this; // $this equals the instance of FooClass
};

$foo = new FooClass();
$foo->my_method(); // outputs: 'Hello JavaScript!!!'
```

### Runtime Definition

```php
class FooClass
{
    use Javascript;
}

$foo = new FooClass();

if (!is_callable($foo->prototype()->my_method)) {
    $foo->prototype()->my_method = function() {
        echo 'Hello JavaScript!!!';
    };
}

$foo->my_method(); // outputs: 'Hello JavaScript!!!'

$bar = new FooClass();
$bar->my_method(); // still outputs: 'Hello JavaScript!!!'
```

### External Scope Variables

```php
class FooClass
{
    use Javascript;
}

$message = 'Hello JavaScript!!!';

FooClass::prototype()->my_method = function() use ($message) {
    echo $message;
};

$foo = new FooClass();
$foo->my_method(); // outputs: 'Hello JavaScript!!!'
```

### Prototype Inheritance
```php
class FooClass
{
    use Javascript;
}

class BarClass extends FooClass
{
    use Javascript;
}

FooClass::prototype()->my_method = function() {
    echo 'Hello JavaScript!!!';
};

$bar = new BarClass();
$bar->my_method(); // outputs: 'Hello JavaScript!!!' as defined on parent FooClass
```

## Tests

PHPUnit `bin/phpunit`
