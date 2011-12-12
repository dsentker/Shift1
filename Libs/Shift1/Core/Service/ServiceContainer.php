<?php
namespace Shift1\Core\Service;

use Shift1\Core\Exceptions\ClassNotFoundException;
use Shift1\Core\Exceptions\ServiceException;

class ServiceContainer implements iServiceContainer {

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
        $serviceWrapperNS = '\\Application\\Services\\' . \ucfirst($serviceName) . 'Service';

        if(!\class_exists($serviceWrapperNS)) {
            throw new ClassNotFoundException($serviceWrapperNS . ' not found');
        }

        if($serviceWrapperNS::$isSingleton && $this->serviceIsRunning($serviceName)) {
            return $this->getRunningService($serviceName);
        }

        $serviceWrapper = new $serviceWrapperNS;
        $instance = $serviceWrapper->getInstance();
        $this->activeServices[$serviceName] = $instance;
        return $instance;
    }

    public function has($serviceName) {
        $serviceWrapperNS = '\\Application\\Services\\' . \ucfirst($serviceName) . 'Service';
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