<?php
namespace Shift1\Core\Service\Container;

use Shift1\Core\Exceptions\ClassNotFoundException;
use Shift1\Core\Exceptions\ServiceException;

class ServiceContainer implements ServiceContainerInterface {

    const SERVICENAME_SUFFIX = 'Service';

    /**
     * @var string
     */
    protected $serviceNamespace;

    /**
     * @param string $serviceNamespace
     */
    public function __construct($serviceNamespace) {
        $this->serviceNamespace = \trim($serviceNamespace, '\\');
    }

    /**
     * @return string
     */
    protected function getServiceNamespace() {
        return '\\' . $this->serviceNamespace . '\\';
    }

    /**
     * @var array
     */
    protected $activeServices = array();

    /**
     * @throws \Shift1\Core\Exceptions\ClassNotFoundException
     * @param string $serviceName
     * @return AbstractService
     */
    public function get($serviceName) {

        $serviceWrapperNS = $this->getServiceNamespace() . \ucfirst($serviceName) . self::SERVICENAME_SUFFIX;

        if(!\class_exists($serviceWrapperNS)) {
            throw new ClassNotFoundException($serviceWrapperNS . ' not found');
        }

        if($serviceWrapperNS::getIsSingleton() && $this->serviceIsRunning($serviceName)) {
            return $this->getRunningService($serviceName);
        }

        /** @var $serviceWrapper AbstractService */
        $serviceWrapper = new $serviceWrapperNS;

        if($serviceWrapper->hasNecessitatesServices()) {
            foreach($serviceWrapper->getNecessitatedServices() as $service) {
                $serviceInstance = $this->get($service);
                $serviceWrapper->inject($service, $serviceInstance);
            }
        }

        $instance = $serviceWrapper->getInstance();
        $this->activeServices[$serviceName] = $instance;
        return $instance;
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName) {
        $serviceWrapperNS = $this->getServiceNamespace() . \ucfirst($serviceName) . self::SERVICENAME_SUFFIX;
        return \class_exists($serviceWrapperNS);
    }

    /**
     * @param $serviceName
     * @return bool
     */
    protected function serviceIsRunning($serviceName) {
        return isset($this->activeServices[$serviceName]);
    }

    /**
     * @return array
     */
    public function getRunningServices() {
        return $this->activeServices;
    }

    /**
     * @throws \Shift1\Core\Exceptions\ServiceException
     * @param string $serviceName
     * @return \Shift1\Core\Service\AbstractService
     */
    protected function getRunningService($serviceName) {
        if(!$this->serviceIsRunning($serviceName)) {
            throw new ServiceException('Service ' . $serviceName . ' is not running now');
        }
        return $this->activeServices[$serviceName];
    }

}