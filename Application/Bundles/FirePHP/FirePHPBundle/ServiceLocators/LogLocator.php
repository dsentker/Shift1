<?php
namespace Bundles\FirePHP\FirePHPBundle\ServiceLocators;

use Bundles\Shift1\CoreBundle\ServiceLocators\LogLocator as Shift1LogLocator;
use Bundles\FirePHP\FirePHPBundle\LogWriter\FirePHPWriter;
use Shift1\Core\InternalFilePath;

class LogLocator extends Shift1LogLocator {

    public function __construct() {
        parent::__construct();
        $this->dependsOn('firePHP');
    }

    public function prepare(&$logger) {
        parent::prepare($logger);
        /** @var \Shift1\Log\Logger $logger */
        $firePHPWriter = new FirePHPWriter($this->getService('firePHP'));
        $logger->addWriter($firePHPWriter);
    }

}
?>
