<?php
namespace Shift1\Core\Service\Container;

use Shift1\Core\Service\Exceptions\ServiceContainerException;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Locator\ServiceLocatorInterface;
use Shift1\Core\Service\Locator\ParameterLocator;

class ServiceContainer implements ServiceContainerInterface {

    /**
     * @var array
     */
    protected $serviceLocators = array();

    /**
     * @return \Shift1\Core\Service\Container\ServiceContainer
     */
    public function __construct() {
        $this->add('parameter', new ParameterLocator());
    }

    /**
     * @throws \Shift1\Core\Service\Exceptions\ServiceContainerException
     * @param string $serviceName
     * @return \Shift1\Core\Service\Locator\AbstractServiceLocator
     */
    public function get($serviceName) {

        $serviceLocator = $this->getServiceLocator($serviceName);


        if($serviceLocator->getIsSingleton() && $this->serviceIsRunning($serviceName)) {
            return $this->getRunningService($serviceName);
        }

        if($serviceLocator->hasDependentServices()) {
            foreach($serviceLocator->getDependentServices() as $service) {
                $serviceInstance = $this->get($service);
                $serviceLocator->injectService($service, $serviceInstance);
            }
        }

        $serviceLocator->initialize();

        $instance = $serviceLocator->getInstance();

        if($instance instanceof ContainerAccess) {
            $instance->setContainer($this);
        }

        RunningServicesRegistry::add($serviceName, $instance);
        return $instance;
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName) {
        return !empty($this->serviceLocators[$serviceName]);
    }

    public function add($locatorKey, ServiceLocatorInterface $locator) {
        $this->serviceLocators[$locatorKey] = $locator;
    }

    /**
     * @param $serviceName
     * @return ServiceLocatorInterface
     * @throws \Shift1\Core\Service\Exceptions\ServiceContainerException
     */
    public function getServiceLocator($serviceName) {
        if(!$this->has($serviceName)) {
            throw new ServiceContainerException("ServiceLocator key '{$serviceName}' not found.", ServiceContainerException::LOCATOR_NOT_FOUND);
        } elseif(!($this->serviceLocators[$serviceName] instanceof ServiceLocatorInterface)) {
            throw new ServiceContainerException("'{$serviceName}' must be an instance of ServiceLocatorInterface", ServiceContainerException::BAD_INTERFACE);
        }
        return $this->serviceLocators[$serviceName];
    }

    /**
     * @return array
     */
    public function getServiceLocatorKeys() {
        return \array_keys($this->serviceLocators);
    }

    /**
     * @param $serviceName
     * @return bool
     */
    protected function serviceIsRunning($serviceName) {
        return RunningServicesRegistry::has($serviceName);
    }

    /**
     * @return array
     */
    public function getRunningServices() {
        return RunningServicesRegistry::getAll();
    }

    public function getRunningServiceNames() {
        return \array_keys(self::getRunningServices());
    }

    /**
     *
     * @param string $serviceName
     * @throws \Shift1\Core\Service\Exceptions\ServiceContainerException
     * @return \Shift1\Core\Service\Locator\AbstractServiceLocator
     */
    protected function getRunningService($serviceName) {
        /*
         * At this point, the given $serviceName has to
         * be sanitized / transformed. So there is no need
         * to transform it again.
         */
        if(!$this->serviceIsRunning($serviceName)) {
            throw new ServiceContainerException('Service ' . $serviceName . ' is not running now', ServiceContainerException::SERVICE_NOT_RUNNING);
        }
        return RunningServicesRegistry::get($serviceName);
    }

}