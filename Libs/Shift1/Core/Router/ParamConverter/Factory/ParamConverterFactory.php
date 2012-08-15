<?php
namespace Shift1\Core\Router\ParamConverter\Factory;

use Shift1\Core\Router\ParamConverter\AbstractParamConverter;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Exceptions\ParamConverterException;

class ParamConverterFactory implements ContainerAccess {

    protected $container;

    protected $activeConverter = array();

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }

    /**
     * @throws \Shift1\Core\Exceptions\ParamConverterException
     * @param null|string $converterName Leave empty for default converter
     * @return \Shift1\Core\Router\ParamConverter\AbstractParamConverter
     */
    public function createConverter($converterName = null) {

        $converterName = (null === $converterName) ? 'DefaultConverter' : \ucfirst($converterName);
        $converterClass = $this->getConverterInstance($converterName);
        return $converterClass;

    }

    protected function isLoaded($converterName) {
        return isset($this->activeConverter[$converterName]);
    }

    /**
     * @throws \Shift1\Core\Exceptions\ParamConverterException
     * @param string $converterName
     * @return \Shift1\Core\Router\ParamConverter\AbstractParamConverter
     */
    protected function getConverterInstance($converterName) {

        if($this->isLoaded($converterName)) {
            return $this->activeConverter[$converterName];
        }

        $converterName;

        if(!\class_exists($converterName)) {
            throw new ParamConverterException("Converter {$converterName} does not exist!");
        }

        $converterClass = new $converterName;

        if(!($converterClass instanceof AbstractParamConverter)) {
            throw new ParamConverterException("Converter {$converterName} must be an instance of AbstractParamConverter!");
        }

        $converterClass->setContainer($this->getContainer());

        $this->activeConverter[$converterName] = $converterClass;

        return $converterClass;



    }

}
