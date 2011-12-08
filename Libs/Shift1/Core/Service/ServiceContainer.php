<?php
namespace Shift1\Core\Service;

use Shift1\Core\Exceptions\ClassNotFoundException;

class ServiceContainer implements iServiceContainer {

    protected $activeServices = array();
    
    public function get($serviceName) {
        $serviceWrapperNS = '\\Application\\Services\\' . \ucfirst($serviceName) . 'Service';

        if(!\class_exists($serviceWrapperNS)) {
            throw new ClassNotFoundException($serviceWrapperNS . ' not found');
        }
        
        $serviceWrapper = new $serviceWrapperNS;
        return $serviceWrapper->getInstance();
    }

}
?>
