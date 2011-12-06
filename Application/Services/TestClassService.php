<?php
namespace Application\Services;

use Shift1\Core\Service\AbstractService;

class TestClassService extends AbstractService {
    
    public function __construct() {
        $this->setClassNamespace('\Some\Name\Space\TestClass', 'Libs\TestLib\TestClass.php');
    }
    
    public function prepare(&$obj) {
        $obj->setString('OMG i am a string');
    }
    
}
