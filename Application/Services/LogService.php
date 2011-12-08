<?php
namespace Application\Services;

use Shift1\Core\Service\AbstractService;
use Shift1\Log\Writer;
use Shift1\Core\InternalFilePath;

class LogService extends AbstractService {
    
    public function __construct() {
        $this->setClassNamespace('\Shift1\Log\Logger');
    }


    public function prepare(&$logger) {
        /** @var \Shift1\Log\Logger $logger */
        $logger->addWriter(new Writer\FileWriter(new InternalFilePath('Application\Logs\log.txt')));
    }
    
}
?>
