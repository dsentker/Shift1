<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Exceptions as E;
use Shift1\Core\View\View;
use Shift1\Core\FrontController;
use Shift1\Core\Request\Request;


/**
 *
 * IndexController
 *
 *
 **/
class IndexController extends ParentController {

    public function init() {
        parent::init();
        /** @var \Shift1\Log\Logger $logger */
        #$logger = FrontController::getInstance()->getServiceContainer()->get('Log');
        #$logger->log('IndexController initiated', 'debug');
    }

    public function indexAction() {
        return new Response('<pre>' . print_r($this->getParams(), true));
    }

    public function getLogoAction() {
        try {
            $file = new \Shift1\Core\InternalFilePath('/logo.jpg');
            $response = \Shift1\Core\Response\Generator\DownloadableFileGenerator::factory()->setFile($file)->setFileName('Name der Datei.jpg')->getResponse();
        } catch(E\FileNotFoundException $e) {
            exit('Download not available. ' . $e->getMessage());
        }

        return $response;
    }

    public function viewAction() {
        $this->view->sub = 'A text for placeholder "sub"';
        $this->view->inVal = '">a</a>';
        return new Response($this->getView()->render());
    }

    public function logTestAction() {
        /** @var \Shift1\Log\Logger $logger */
        $logger = FrontController::getInstance()->getServiceContainer()->get('Log');
        $logger->log('This is an debug information');
        $logger->log('This is a warning log entry', 'warn');

        return new Response($logger->getLogCount() . 'entries added.');
    }

    public function exceptionAction() {
        throw new E\ApplicationException('This is an exception!');
    }

    public function errorAction() {
        \trigger_error('Foo != Bar!');
    }

    public function HMVCAction() {
        $fooResponse = $this->internalRequest('Foo', 'testFoo', $this->getParams());
        $this->view->foo = $fooResponse->getContent()->render(false);
        #$this->view->foo = 'HA';
        return new Response($this->getView()->render());
    }

}