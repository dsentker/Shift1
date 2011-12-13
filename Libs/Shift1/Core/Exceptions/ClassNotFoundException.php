<?php
namespace Shift1\Core\Exceptions;

class ClassNotFoundException extends \Exception {
    
    public function __construct($ns) {
        parent::__construct('Class not found: ' . $ns);
    }
    
}