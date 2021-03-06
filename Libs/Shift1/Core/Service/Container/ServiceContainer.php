<?php
namespace Shift1\Core\Service\Container;

use Shift1\Core\Exceptions\ClassNotFoundException;
use Shift1\Core\Exceptions\ServiceException;
use Shift1\Core\Service\ContainerAccess;

class ServiceContainer implements ServiceContainerInterface {

    const LOCATOR_CLASS_SUFFIX = 'Locator';

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
     * @return \Shift1\Core\Service\Locator\AbstractServiceLocator
     */
    public function get($serviceName) {

        $serviceName = $this->transformServiceName($serviceName);
        $serviceWrapperNS = $this->getServiceNamespace() . $serviceName . self::LOCATOR_CLASS_SUFFIX;

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

        RunningServicesRegistry::add($serviceName, $instance);
        return $instance;
    }

    /**
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName) {
        $serviceWrapperNS = $this->getServiceNamespace() . $this->transformServiceName($serviceName) . self::LOCATOR_CLASS_SUFFIX;
        return \class_exists($serviceWrapperNS);
    }

    /**
     * @param $serviceName
     * @return bool
     */
    protected function serviceIsRunning($serviceName) {
        $serviceName = $this->transformServiceName($serviceName);
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
     * @throws \Shift1\Core\Exceptions\ServiceException
     * @param string $serviceName
     * @return \Shift1\Core\Service\Locator\AbstractServiceLocator
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
        return RunningServicesRegistry::get($serviceName);
    }

}