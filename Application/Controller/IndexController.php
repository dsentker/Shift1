<?php
namespace Application\Controller;

use Shift1\Core\Response\Response;

class IndexController extends ParentController {

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
        
    
        return new Response('Hiho! ' . $baz);
    }

    

    
}


?>
