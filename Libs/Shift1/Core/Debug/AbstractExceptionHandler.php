<?php
namespace Shift1\Core\Debug;

abstract class AbstractExceptionHandler {

    final public function register() {
        \set_exception_handler(array($this, 'handle'));
    }

    abstract public function handle(\Exception $e);

}