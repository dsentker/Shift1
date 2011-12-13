<?php
namespace Shift1\Core\Debug;

use Shift1\Core\Shift1Object;
 
abstract class AbstractExceptionHandler extends Shift1Object {

    final public static function register() {
        $handler = new static();
        /*
         * Remove the leading cross
         * to uncomment and test this
         * handler.
         */
        #$handler->handle(new \Exception('Test')); exit();

        \set_exception_handler(array($handler, 'handle'));
    }

    abstract public function handle(\Exception $e);

}