<?php
namespace Shift1\Core\View\Renderer;

use Shift1\Core\Service\ContainerAccess;

abstract class AbstractRenderer implements RendererInterface, ContainerAccess {

    /**
     * @var array
     */
    protected $vars = array();

    /**
     * @var string
     */
    protected $file;

    /**
     * @var \Shift1\Core\Service\Container\ServiceContainerInterface
     */
    protected $container;

    /**
     * @param array $vars
     * @return void
     */
    public function setVars(array $vars) {
        $this->vars = $vars;
    }

    /**
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     * @param string $tplFile
     * @return void
     */
    public function setTemplate($tplFile) {
        $this->file = $tplFile;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->file;
    }


    /**
     * @param View|string $view
     * @param string $slotName
     * @return ViewInterface
     */
    public function wrappedBy($view, $slotName = 'content') {
        if(!($view instanceof ViewInterface)) {
            $view = $this->newSelf($view);
        }
        $this->wrapperView = $view;
        $this->wrapperSlot = $slotName;

        return $this->wrapperView;
    }

    /**
     * @return bool
     */
    public function hasWrapper() {
        return $this->wrapperView instanceof ViewInterface;
    }

    /**
     * @return null|View
     */
    public function getWrapper() {
        return $this->wrapperView;
    }

    /**
     * @return \Shift1\Core\Service\Container\ServiceContainerInterface
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @param string $helper
     * @return mixed
     */
    protected function helper($helper) {
        return $this->getContainer()->get($helper);
    }

    /**
     * Access to ServiceContainer
     * This method prevents the access to another service
     * than viewHelper services
     *
     * @param \Shift1\Core\Service\Container\ServiceContainerInterface $container
     * @return void
     */
    public function setContainer(ServiceContainerInterface $container) {
        $container = clone $container;
        $container->extendServiceNamespace('ViewHelper');
        $this->container = $container;
    }

}