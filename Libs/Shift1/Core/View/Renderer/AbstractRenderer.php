<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Service\Container\ServiceContainerInterface;

abstract class AbstractRenderer implements RendererInterface, ContainerAccess {

    protected $container;

    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }

    public function getContainer() {
        return $this->container;
    }

}