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

<<<<<<< HEAD
?>
=======
?>
>>>>>>> 5a1f9667b5d83042497c12de63ce1a889224cd51
