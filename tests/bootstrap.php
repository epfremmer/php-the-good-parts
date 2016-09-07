<?php
/**
 * File bootstrap.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

/**
 * PHPUnit Bootstrap
 *
 * Registers the Composer autoloader
 */
call_user_func(function() {
    if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
        throw new \RuntimeException('Did not find vendor/autoload.php. Did you run "composer install --dev"?');
    }

    require_once $autoloadFile;
});
