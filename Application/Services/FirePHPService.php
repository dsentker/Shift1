<?php
namespace Application\Services;

use Shift1\Core\Service\AbstractService;
use Shift1\Log\Writer;
use Shift1\Core\InternalFilePath;

class FirePHPService extends AbstractService {

    static public $isSingleton = true;
    
    public function __construct() {
        $this->setClassNamespace('\FirePHP', 'Libs/FirePHPCore/fb.php');
    }

    public function getInstance() {
        /** @var \FirePHP $fb */
        $fb = parent::getInstance();
        return $fb::getInstance(true);

    }
    
}
?>
