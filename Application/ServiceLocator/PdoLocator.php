<?php
namespace Application\ServiceLocator;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

class PdoLocator extends AbstractServiceLocator {
    
    public function __construct() {
        $this->setClassNamespace('\PDO');

        $config = $this->getApp()->getConfig();
        

        $hostString = 'mysql:host=' . Config::database('host') . ';dbname=' . Config::database('database');

        $constructorArgs = array(

        );
        $this->setConstructorArgs($constructorArgs);
    }

    

    public function prepare(&$pdo) {
        /** @var \PDO $pdo */
        
    }
    
}
