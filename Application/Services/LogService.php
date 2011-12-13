<?php
namespace Application\Services;

use Shift1\Core\Service\AbstractService;
use Shift1\Log\Writer;
use Shift1\Core\InternalFilePath;

class LogService extends AbstractService {

    static public $isSingleton = true;
    
    public function __construct() {
        $this->setClassNamespace('\Shift1\Log\Logger');
    }

    public function prepare(&$logger) {
        /** @var \Shift1\Log\Logger $logger */

        $fileWriter = new Writer\FileWriter(new InternalFilePath('Application\Logs\log.txt'));
        $fileWriter->setLevel('debug');

        $firePHPWriter = new Writer\FirePHPWriter();

        $screenWriter = new Writer\ScreenWriter();
        #$screenWriter->setLevel('notice');

        $logger->addWriter($fileWriter);
        $logger->addWriter($screenWriter);
        $logger->addWriter($firePHPWriter);
        $logger->addWriter(new Writer\NullWriter());

    }
    
}
?>
