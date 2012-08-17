<?php
namespace Shift1\Core\Routing\ParamConverter;

use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
 
abstract class AbstractParamConverter implements ParamConverterInterface, ContainerAccess {

    protected $container;

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }




}
