<?php
namespace Shift1\Core\View;

use Shift1\Core\Exceptions\ViewException;
use Shift1\Core\Exceptions\ClassNotFoundException;
use Shift1\Core\Exceptions\ServiceException;
use Shift1\Core\View\ControllerViewReloader\ControllerViewReloader;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Response\Renderable;
use Shift1\Core\InternalFilePath;


class View implements ViewInterface, ContainerAccess, Renderable {

    /**
     * @var null|InternalFilePath
     */
    protected $viewFile;

    /**
     * @var string
     */
    protected $viewPath;

    /**
     * Wheter to throw an exception or not
     * @var bool
     */
    protected $throw = true;

    /**
     * @var StdClass
     */
    protected $config;

    /**
     * @var Renderer\RendererInterface
     */
    protected $renderer;

    /**
     * @var ServiceContainerInterface
     */
    protected $container;

    /**
     * @var TemplateAnnotationReader\TemplateAnnotationReader
     */
    protected $annotationReader;

    /**
     * @var \Shift1\Core\View\ControllerViewReloader\ControllerViewReloader
     */
    protected $controllerViewReloader;

    /**
     * @var Shift1\Core\View\VariableSet\VariableSetInterface
     */
    protected $variableSet;

    /**
     * @var array
     */
    protected $slots = array();


    /**
     * @param \ArrayObject $config
     * @param VariableSet\VariableSetInterface $variableSet
     * @param Renderer\RendererInterface $renderer
     * @param TemplateAnnotationReader\TemplateAnnotationReaderInterface $annotationReader
     * @param ControllerViewReloader\ControllerViewReloader $controllerViewReloader
     *
     */
    public function __construct(
        $config,
        VariableSet\VariableSetInterface $variableSet,
        Renderer\RendererInterface $renderer,
        TemplateAnnotationReader\TemplateAnnotationReaderInterface $annotationReader,
        ControllerViewReloader $controllerViewReloader
) {

        $this->config = $config;
        $this->variableSet = $variableSet;
        $this->renderer = $renderer;
        $this->annotationReader = $annotationReader;
        $this->controllerReloader = $controllerViewReloader;

	}

    /**
     * @return \Shift1\Core\Service\Container\ServiceContainerInterface
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Access to ServiceContainer

     * @param \Shift1\Core\Service\Container\ServiceContainerInterface $container
     * @return void
     */
    public function setContainer(ServiceContainerInterface $container) {
        $this->container = $container;
    }


    /**
     * @return \Shift1\Core\View\VariableSet\VariableSetInterface
     */
    public function getVariableSet() {
        return $this->variableSet;
    }

    /**
     * @return null|Renderer\RendererInterface
     */
    public function getRenderer() {
        return $this->renderer;
    }

    /**
     * @return void
     */
    public function disableExceptions() {
        $this->throw = false;
    }

    /**
     * @return void
     */
    public function enableExceptions() {
        $this->throw = true;
    }

    /**
     * @return bool
     */
    public function isThrowingExceptions() {
        return $this->throw;
    }

    /**
     * @param string $file
     * @return string
     */
    protected function completeViewFilename($file) {
        if(\strpos($file, '.') === false) {
            $file .= '.' . $this->config->defaultFileExt;
        }
        return $file;
    }

    /**
     * @param string $viewFile
     * @param bool $useDefaultViewFilePath
     * @return View
     */
    public function setViewFile($viewFile, $useDefaultViewFilePath = true) {
        if(true === $useDefaultViewFilePath) {
            $viewFile = $this->config->defaultSrcPath . '/' . $viewFile;
        }

        $this->viewFile = new InternalFilePath($this->completeViewFilename($viewFile));

        return $this;
    }

    /**
     * @return InternalFilePath
     */
    public function getViewFile() {
        return $this->viewFile;
    }

    /**
     * @param string $varKey
     * @param mixed $varValue
     * @return View
     */
	public function assign($varKey, $varValue) {

        $varKey = \trim($varKey);

        if(empty($varKey)) {
            throw new ViewException('Assignment failed: Empty keys are not allowed!');
        }

        $this->variableSet->add($varKey, $varValue);
        return $this;
	}

    /**
     * @param array $vars
     * @return View
     */
	public function assignArray(array $vars) {
        foreach($vars as $key => $var) {
            $this->assign($key, $var);
        }
        return $this;
	}

    public function helper($service) {
        return $this->getContainer()->get('viewHelper.' . $service);
    }

    /**
     * @param string $key
     * @param mixed $val
     * @return void
     */
	public function __set($key, $val) {
        $this->assign($key, $val);
	}

    public function __clone() {
        $this->annotationReader = clone $this->annotationReader;
        $this->slots = array();
    }

    /**
     * @param $slotName
     * @return mixed
     */
    public function slot($slotName) {
        return $this->slots[$slotName];
    }

    /**
     * @param $slotName
     * @param mixed $view
     * @return void
     */
    public function setSlot($slotName, $view) {
        $this->slots[$slotName] = $view;
    }

    /**
     * @return string
     */
	public function __toString() {

        $wasThrowing = $this->isThrowingExceptions();

        // __toString always excepts a string, not an exception.
        $this->disableExceptions();

        $content = $this->render();
        if($wasThrowing) $this->enableExceptions();

        return $content;
	}

    /**
     * @param string|Shift1\Core\InternalFilePath $file
     * @param bool $useDefaultViewFilePath
     * @return bool
     */
    public function fileExists($file, $useDefaultViewFilePath = true) {

        if(true === $useDefaultViewFilePath) {
            $file = $this->config->defaultSrcPath . '/' . $file;
        }

        if(!($file instanceof InternalFilePath)) {
            $file = new InternalFilePath($this->completeViewFilename($file));
        }
        return $file->exists();
    }

    /**
     * @return string
     */
    public function renderPartial() {
        return $this->getRenderer()->render($this);
    }

    /**
     * @return string
     */
	public function render() {

        $content = $this->getRenderer()->render($this);

        $this->annotationReader->setTemplate($this->getViewFile());
        $this->annotationReader->parse();

        if($this->annotationReader->hasAnnotation('hasParent') && $this->annotationReader->hasAnnotationParameterCount('hasParent', 2) ) {

            $parentViewOptions = $this->annotationReader->getAnnotationParameter('hasParent');
            $parentViewFile = $parentViewOptions[0];
            $parentViewSlot = $parentViewOptions[1];

            if($this->annotationReader->hasAnnotation('renderedByController')) {
                if($this->annotationReader->hasAnnotationParameterCount('renderedByController', 1, 'min')) {
                    $definition = $this->annotationReader->getAnnotationParameter('renderedByController');
                    $parentView = $this->controllerViewReloader->loadByControllerDefinition($definition[0]);
                } else {
                    $parentView = $this->controllerViewReloader->loadByTemplateLocation($parentViewFile);
                }

            } else {
                // no controller for this parent view
                $parentView = clone $this;
                $parentView->setViewFile($parentViewFile);
            }

            $parentView->setSlot($parentViewSlot, $content);
            $content = $parentView->render();
            
        }

        return $content;

	}

    /**
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function setActionView($controller, $action) {

        $suggestedViewFile = $controller . '/' . $action;

        switch(true) {
            case $this->fileExists($suggestedViewFile):
                $this->setViewFile($suggestedViewFile);
                break;
            case $this->fileExists('index'):
                $this->setViewFile('index');
                break;
            default:
                // No view file detected
                $this->setViewFile('Libs/Shift1/Core/Resources/Views/viewNotFound', false);
                return false;
        }

        return true;
    }

}