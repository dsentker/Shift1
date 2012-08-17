<?php
namespace Shift1\Core\Routing\ParamConverter\Factory;

use Shift1\Core\Routing\ParamConverter\ParamConverterInterface;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Routing\Exceptions\ParamConvertingException;

class ParamConverterFactory implements ContainerAccess {

    /**
     * @var \Shift1\Core\Service\Container\ServiceContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $activeConverter = array();

    /**
     * @param ServiceContainerInterface $container
     */
    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @return ServiceContainerInterface
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @param string $converterName
     * @return bool
     */
    protected function isLoaded($converterName) {
        return isset($this->activeConverter[$converterName]);
    }

    /**
     * @param string $converterNamespace
     * @return mixed
     * @throws \Shift1\Core\Routing\Exceptions\ParamConvertingException
     */
    public function createConverter($converterNamespace) {

        if($this->isLoaded($converterNamespace)) {
            return $this->activeConverter[$converterNamespace];
        }

        if(!\class_exists($converterNamespace)) {
            throw new ParamConvertingException("Converter {$converterNamespace} does not exist!", ParamConvertingException::PARAM_CONVERTER_CLASS_INVALID);
        }

        $converterClass = new $converterNamespace;

        if(!($converterClass instanceof ParamConverterInterface)) {
            throw new ParamConvertingException("Converter {$converterNamespace} must be an instance of ParamConverterInterface!", ParamConvertingException::PARAM_CONVERTER_INTERFACE_INVALID);
        }

        if($converterClass instanceof ContainerAccess) {
            $converterClass->setContainer($this->getContainer());
        }

        $this->activeConverter[$converterNamespace] = $converterClass;
        return $converterClass;

    }

}
