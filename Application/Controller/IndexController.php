<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;
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

        /** @var \Shift1\Log\Logger $logger */
        $logger = $this->getApp()->getServiceContainer()->get('Log');
        #$logger->log('TEST');
        $logger->log('Foo bar ist guuut');


        #var_dump(4 < 1024); exit();
        return new Response($this->view->render());
    }

    

    
}


?>
