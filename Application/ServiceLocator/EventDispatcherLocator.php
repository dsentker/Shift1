<?php
namespace Application\ServiceLocator;

use Shift1\Core\Service\Locator\AbstractServiceLocator;

use Symfony\Component\Finder\Finder;
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

        // Pfade setzen
        // TODO: Konfigurierbar machen
        $path = BASEPATH . DIRECTORY_SEPARATOR . 'Application' . DIRECTORY_SEPARATOR . 'Listener';
        $namespace = '\Application\Listener\\';

        // Alle Listener im Ordner finden
        $finder = new Finder();
        $iterator = $finder
            ->files()
            ->name('*Listener.php')
            ->depth(0)
            ->in($path);

        foreach($iterator as $listener)
        {
            $class = $namespace . $listener->getBasename('.php');

            // Listener hinzufügen
            if(class_exists($class))
                $serviceInstance->addSubscriber(new $class);
        }
    }
}