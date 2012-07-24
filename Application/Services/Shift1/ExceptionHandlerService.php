<?php
namespace Application\Services\Shift1;

use Shift1\Core\Service\AbstractService;
use Shift1\Core\Debug\HTMLResponseExceptionHandler;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Config\File;


class ExceptionHandlerService extends AbstractService {

    public static $isSingleton = true;

    public function __construct() {
        $this->necessitate('shift1.context');
        $this->necessitate('shift1.view');

    }

    public function initialize() {

        $handlerNS = '\\Shift1\\Core\Debug\\';

        switch($this->get('shift1.context')->environment) {
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
            $view = $this->get('shift1.view');
            $view->setViewFile('Libs/Shift1/Core/Resources/Views/exceptionView', false);
            $serviceInstance->setExceptionView($view);
        }




    }

}