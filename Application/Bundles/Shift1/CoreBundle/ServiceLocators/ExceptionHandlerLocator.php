<?php
namespace Bundles\Shift1\CoreBundle\ServiceLocators;

use Shift1\Core\Service\Locator\AbstractServiceLocator;
use Shift1\Core\Debug\HTMLResponseExceptionHandler;
use Shift1\Core\Bundle\Definition\TemplateDefinition;

class ExceptionHandlerLocator extends AbstractServiceLocator {

    public static $isSingleton = true;

    public function __construct() {
        $this->dependsOn(array(
            'parameter',
            'view',
        ));
    }

    public function initialize() {

        $handlerNS = '\\Shift1\\Core\Debug\\';

        switch($this->getService('parameter')->environment) {
            case 'production':
            case 'staging':
                $handlerNS .= 'SilentExceptionHandler';
                break;
            default:
                $handlerNS .= 'HTMLResponseExceptionHandler';
        }

        $this->setClassNamespace($handlerNS);

    }

    public function prepare(&$serviceInstance) {
        /**
         * @var $view \Shift1\Core\View\View
         * @var $serviceInstance \Shift1\Core\Debug\AbstractExceptionHandler|\Shift1\Core\Debug\HTMLExceptionHandler
         */

        if($serviceInstance instanceof HTMLResponseExceptionHandler) {
            $view = $this->getService('view');
            $templateDefinition = new TemplateDefinition('shift1:core:exceptionView');
            $view->setViewFile($templateDefinition);
            $serviceInstance->setExceptionView($view);
        }




    }

}