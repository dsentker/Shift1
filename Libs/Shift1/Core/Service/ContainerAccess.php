<?php
namespace Shift1\Core\Service;

use Shift1\Core\Service\Container\ServiceContainerInterface;

interface ContainerAccess {

    /**
     * @abstract
     * @param Container\ServiceContainerInterface $container
     * @return void
     */
    function setContainer(ServiceContainerInterface $container);

    /**
     * @abstract
     * @return Container\ServiceContainerInterface $container
     */
    function getContainer();
}
