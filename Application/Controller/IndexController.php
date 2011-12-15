<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
use Shift1\Core\Exceptions\ApplicationException;
use Shift1\Core\View\View;

class IndexController extends ParentController {

    public function init() {
        /** @var \Shift1\Log\Logger $logger */
        $logger = $this->getApp()->getServiceContainer()->get('Log');
        $logger->log('IndexController initiated', 'debug');
    }

    public function indexAction() {
        return new Response('<pre>' . print_r($this->getParams(), 1));
    }

    public function getLogoAction() {
        $file = new \Shift1\Core\InternalFilePath('Shift1/logo.jpg');
        $response = \Shift1\Core\Response\Generator\DownloadableFileGenerator::factory()->setFile($file)->setFileName('Name der Datei.jpg')->getResponse();
        return $response;
    }

    public function viewAction() {
        $this->getView()->wrappedBy(new View('subpage'));
        return new Response($this->getView()->render());
    }

    public function logTestAction() {
        /** @var \Shift1\Log\Logger $logger */
        $logger = $this->getApp()->getServiceContainer()->get('Log');
        $logger->log('This is an debug information');
        $logger->log('This is a warning log entry', 'warn');

        return new Response($logger->getLogCount() . 'entries added.');
    }

    public function execptionAction() {
        throw new ApplicationException('This is an exception!');
        
    }

}