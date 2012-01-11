<?php
namespace Shift1\Core\Service\Container;

use Shift1\Core\Exceptions\ClassNotFoundException;
use Shift1\Core\Exceptions\ServiceException;
use Shift1\Core\Service\ContainerAccess;

class ServiceContainer implements ServiceContainerInterface {

    const SERVICENAME_SUFFIX = 'Service';

    /**
     * @var string
     */
    protected $serviceNamespace;

    /**
     * @var array
     */
    protected $activeServices = array();

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
     * @param string $serviceName
     * @return string
     */
    protected function transformServiceName($serviceName) {
        $serviceNameParts = \explode('.', $serviceName);
        foreach($serviceNameParts as &$part) {
            $part = \ucfirst($part);
        }
        return \implode('\\', $serviceNameParts);
    }

    /**
     * @throws \Shift1\Core\Exceptions\ClassNotFoundException
     * @param string $serviceName
     * @return AbstractService
     */
    public function get($serviceName) {

        $serviceName = $this->transformServiceName($serviceName);
        $serviceWrapperNS = $this->getServiceNamespace() . $serviceName . self::SERVICENAME_SUFFIX;

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

        $serviceWrapper->initialize();

        $instance = $serviceWrapper->getInstance();

        if($instance instanceof ContainerAccess) {
            $instance->setContainer($this);
        }

        $this->activeServices[$serviceName] = $instance;
        return $instance;
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName) {
        $serviceWrapperNS = $this->getServiceNamespace() . $this->transformServiceName($serviceName) . self::SERVICENAME_SUFFIX;
        return \class_exists($serviceWrapperNS);
    }

    /**
     * @param $serviceName
     * @return bool
     */
    protected function serviceIsRunning($serviceName) {
        $serviceName = $this->transformServiceName($serviceName);
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
        /*
         * At this point, the given $serviceName has to
         * be sanitized / transformed. So there is no need
         * to transform it again.
         */
        if(!$this->serviceIsRunning($serviceName)) {
            throw new ServiceException('Service ' . $serviceName . ' is not running now');
        }
        return $this->activeServices[$serviceName];
    }

    /**
     * To route the container getter to a specific
     * base namespace
     * 
     * @param string $base
     * @return void
     */
    public function extendServiceNamespace($base) {
        $this->serviceNamespace .= '\\' . \trim($base, '\\');;
    }

}