<?php
namespace Shift1\Core\Debug;

use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;

abstract class AbstractExceptionHandler implements ContainerAccess {

    /**
     * @var \Shift1\Core\Service\Container\ServiceContainerInterface
     */
    protected $container;

    final public function register() {

        \set_exception_handler(array($this, 'handle'));
    }

    abstract public function handle(\Exception $e);


    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }
}