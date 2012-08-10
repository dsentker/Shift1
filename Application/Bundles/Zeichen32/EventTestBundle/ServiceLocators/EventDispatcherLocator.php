<?php
namespace Bundles\Zeichen32\EventTestBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\InternalFilePath;

use Symfony\Component\EventDispatcher\EventSubscriberInterface as EventSubscriber;

class EventDispatcherLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->setClassNamespace('\Symfony\Component\EventDispatcher\EventDispatcher');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $serviceInstance
     */
    public function prepare(&$serviceInstance)
    {
        parent::prepare($serviceInstance);

        $listenerConfigPath = new \Shift1\Core\InternalFilePath('Application/Config/listener.yml');
        $listenerEntries = new \Shift1\Core\Config\File\YamlFile($listenerConfigPath);

        foreach($listenerEntries->toArray() as $namespace => $listenerClasses) {

            foreach($listenerClasses as $listenerClass) {
                $class = '\\' . $namespace . $listenerClass;
                if(\class_exists($class)) {
                    $serviceInstance->addSubscriber(new $class);
                }
            }
        }

    }
}