<?php
namespace Shift1\Core\Exceptions;

class ServiceNotFoundException extends \Exception {
    
    public function __construct($ns) {
        parent::__construct('Service not found: ' . $ns);
    }
    
}