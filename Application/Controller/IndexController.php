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

        $yaml = new \Shift1\Core\InternalFilePath('Application/Config/routes.yml');

        $parsed = \Symfony\Component\Yaml\Yaml::parse($yaml);

        die(print_r($parsed));

        return new Response('Hallo Welt');
    }

    public function downloadSomethingAction() {
        $file = new \Shift1\Core\InternalFilePath('Shift1/logo.jpg');
        $response = \Shift1\Core\Response\Generator\DownloadableFileGenerator::factory()->setFile($file)->setFileName('Name der Datei.jpg')->getResponse();
        return $response;
    }

    public function testAction($baz = 'Fo', $foo = null) {
        $this->getView()->wrappedBy(new View('subpage'));
        return new Response($this->getView()->render());
    }

    public function logAction() {
        $fb = $this->getApp()->getServiceContainer()->get('FirePHP');
        #print_r($fb);
        #$fb->error(array(1,2, 'FOO'));
        $fb->fb(array(1,2,'foo'), 'MyLabel0riz0r', \FirePHP::WARN);
    }

    public function eAction() {
        $foo = 123;
        throw new \Exception('OMG here is an exception!');
        $bar= 345;
    }

}