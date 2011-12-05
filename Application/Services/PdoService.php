<?php
namespace Application\Services;

use Shift1\Core\Service\AbstractService;

class PdoService extends AbstractService {
    
    public function __construct() {
        $this->setClassNamespace('\PDO');
    }
    
}
?>
