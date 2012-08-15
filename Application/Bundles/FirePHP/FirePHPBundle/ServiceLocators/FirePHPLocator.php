<?php
namespace Bundles\FirePHP\FirePHPBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Log\Writer;

class FirePHPLocator extends AbstractServiceLocator {

    static public $isSingleton = true;
    
    public function __construct() {
        $this->setClassNamespace('\FirePHP', 'Libs/vendor/FirePHPCore/fb.php');
    }

    public function getInstance() {
        /** @var \FirePHP $fb */
        $fb = parent::getInstance();
        return $fb::getInstance(true);

    }
    
}