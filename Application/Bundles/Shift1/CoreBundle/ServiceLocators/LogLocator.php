<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Log\Writer;
use Shift1\Core\InternalFilePath;

class LogLocator extends AbstractServiceLocator {

    static public $isSingleton = true;
    
    public function __construct() {
        $this->setClassNamespace('\Shift1\Log\Logger');

        #$this->necessitate('FirePHP');

    }

    public function prepare(&$logger) {
        /** @var \Shift1\Log\Logger $logger */

        $fileWriter = new Writer\FileWriter(new InternalFilePath('Application\Logs\log.txt'));
        $fileWriter->setLevel('debug');

        #$firePHPWriter = new Writer\FirePHPWriter($this->get('FirePHP'));

        $screenWriter = new Writer\ScreenWriter();
        #$screenWriter->setLevel('notice');

        $logger->addWriter($fileWriter);
        #$logger->addWriter($screenWriter);
        #$logger->addWriter($firePHPWriter);
        #$logger->addWriter(new Writer\NullWriter());

    }
    
}
?>
