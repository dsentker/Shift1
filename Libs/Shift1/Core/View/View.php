<?php
namespace Shift1\Core\View;

use Shift1\Core\Exceptions\ViewException;
use Shift1\Core\View\ControllerViewReloader\ControllerViewReloader;
use Shift1\Core\View\Exceptions\ViewFileException;
use Shift1\Core\Service\Container\ServiceContainerInterface;
use Shift1\Core\Service\ContainerAccess;
use Shift1\Core\Response\Renderable;
use Shift1\Core\InternalFilePath;
use Shift1\Core\VariableSet\VariableSetInterface;
use Shift1\Core\Bundle\Definition\TemplateDefinition;
use Shift1\Core\Bundle\Definition\ActionDefinition;
use Shift1\Core\Bundle\Exceptions\DefinitionException;

class View implements ViewInterface, ContainerAccess, Renderable {

    const FILTER_SEPARATOR = ' ';

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
     * @var \StdClass
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
     * @var \Shift1\Core\VariableSet\VariableSetInterface
     */
    protected $variableSet;

    /**
     * @var array
     */
    protected $slots = array();

    /**
     * @var array
     */
    protected $defaultFilter = array();


    /**
     * @param \ArrayObject $config
     * @param VariableSetInterface $variableSet
     * @param Renderer\RendererInterface $renderer
     * @param TemplateAnnotationReader\TemplateAnnotationReaderInterface $annotationReader
     * @param ControllerViewReloader\ControllerViewReloader $controllerViewReloader
     *
     */
    public function __construct(
        \ArrayObject $config,
        VariableSetInterface $variableSet,
        Renderer\RendererInterface $renderer,
        TemplateAnnotationReader\TemplateAnnotationReaderInterface $annotationReader,
        ControllerViewReloader $controllerViewReloader
) {

        $this->config = $config;
        $this->variableSet = $variableSet;
        $this->renderer = $renderer;
        $this->annotationReader = $annotationReader;
        $this->controllerViewReloader = $controllerViewReloader;

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
     * @return \Shift1\Core\VariableSet\VariableSetInterface
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
     * @param string|InternalFilePath $viewFile
     * @return View
     */
    public function setViewFile($viewFile) {

        if(!($viewFile instanceof InternalFilePath)) {
            try {
                $definition = new TemplateDefinition($viewFile);
                $viewFile = $definition->getTemplateFilePath();
            } catch (DefinitionException $e) {
                if($e->getCode() == DefinitionException::TEMPLATE_DEFINITION_INVALID) {
                    throw new ViewFileException("Invalid view file given: '{$viewFile}'. Provide an InternalFilePath instance or a valid template definition.");
                }
            }

        }
        $this->viewFile = $viewFile;
        $this->annotationReader->parse($viewFile->getAbsolutePath());

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
     * @throws \Shift1\Core\Exceptions\ViewException
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

    /**
     * @param string $service
     * @return mixed
     */
    public function helper($service) {
        return $this->getContainer()->get('viewHelper.' . $service);
    }

    /**
     * @param string $var
     * @param string $filterNames
     * @return mixed
     */
    public function filter($var, $filterNames = '') {
        $currentFilter = $this->splitFilter($filterNames);
        $defaultFilter = $this->defaultFilter;
        $filterStack = \array_unique(\array_merge($currentFilter, $defaultFilter));

        foreach($filterStack as $filterName) {
            $locator = 'viewFilter.' . $filterName;
            if ($this->getContainer()->has($locator)) {
                $filter = $this->getContainer()->get($locator);
                /** @var $filter \Shift1\Core\View\Filter\ViewFilterInterface */
                $var = $filter->setVal($var)->getVal();
            }

        }

        return $var;
    }

    /**
     * @param string|array $filterNames
     */
    public function addDefaultFilter($filterNames) {

        $filter = (!is_array($filterNames)) ? $this->splitFilter($filterNames) : $filterNames;
        $this->defaultFilter = \array_merge($this->defaultFilter, $filter);
    }

    /**
     * @param string $filterNames
     * @return array
     */
    protected function splitFilter($filterNames) {
        return \explode(self::FILTER_SEPARATOR, \strtolower($filterNames));
    }

    /**
     * Acts as a getter
     *
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
    public function renderPartial() {
        return $this->getRenderer()->render($this);
    }

    /**
     * @param string $templateFile
     * @return View
     */
    public function createTemplate($templateFile) {
        $template = clone $this;
        $template->setViewFile($templateFile);

        if($template->isRenderedByController()) {
            $templateVars = $template->renderByController()->getVariableSet();
            $template->getVariableSet()->merge($templateVars);
        }

        return $template;
    }

    /**
     * Just a shortcut to ->createTemplate(foo)->renderPartial()
     * Meant to access from a template file
     *
     * @param string $templateFile
     * @return string
     */
    public function renderTemplate($templateFile) {
        return $this->createTemplate($templateFile)->renderPartial();
    }

    /**
     * @return string
     */
	public function render() {

        $content = $this->getRenderer()->render($this);

        if($this->hasParent()) {

            $parentView = $this->createTemplate($this->getParentTemplate());
            $parentViewSlot = $this->getParentSlot();
            $parentView->setSlot($parentViewSlot, $content);
            $content = $parentView->render();
            
        }

        return $content;

	}

    /**
     * @return bool
     */
    protected function hasParent() {
        return ($this->annotationReader->hasAnnotation('hasParent')
            && $this->annotationReader->hasAnnotationParameterCount('hasParent', 2) );
    }

    /**
     * @return null|string
     */
    protected function getParentTemplate() {
        if (!$this->hasParent()) {
            return null;
        }
        $parentViewOptions = $this->annotationReader->getAnnotationParameter('hasParent');
        return $parentViewOptions[0];
    }

    /**
     * @return null|string
     */
    protected function getParentSlot() {
        if (!$this->hasParent()) {
            return null;
        }
        $parentViewOptions = $this->annotationReader->getAnnotationParameter('hasParent');
        return $parentViewOptions[1];
    }

    /**
     * @return ViewInterface|string
     */
    protected function renderByController() {
        if($this->annotationReader->hasAnnotationParameterCount('renderedByController', 1, 'min')) {
            $definition = $this->annotationReader->getAnnotationParameter('renderedByController');
            $actionDefinition = new ActionDefinition($definition[0]);
        } else {
            $thisPath = new InternalFilePath($this->getViewFile());
            $actionDefinition = ActionDefinition::fromTemplateFile($thisPath);
        }

        $view = $this->controllerViewReloader->loadByActionDefinition($actionDefinition);
        return $view;
    }

    /**
     * @return bool
     */
    protected function isRenderedByController() {
        return ($this->annotationReader->hasAnnotation('renderedByController'));
    }

    /**
     * @param string|Shift1\Core\InternalFilePath $file
     * @return bool
     */
    public function fileExists($file) {

        if(!($file instanceof InternalFilePath)) {
            $file = new InternalFilePath($file);
        }
        return $file->exists();
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

}