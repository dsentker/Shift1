<?php
namespace Shift1\Core\Debug;

use Shift1\Core\View\View;
use Shift1\Core\Response\Response;
use Shift1\Core\Response\Header\Header;

class HTMLResponseExceptionHandler extends AbstractExceptionHandler {

    const FETCH_LINES_BEFORE = 6;

    const FETCH_LINES_AFTER = 3;

    public function handle(\Exception $e) {
        $codeLine = $e->getLine();
        $codeFile = \file($e->getFile());
        $codeRows = array();

        foreach($codeFile as $line => $row) {
            if(($codeLine - self::FETCH_LINES_BEFORE) <= $line && ($codeLine + self::FETCH_LINES_AFTER) >= $line) {
                $codeRows[$line+1] = $row;
            }
        }

        $view = $this->getContainer()->get('shift1.view');
        /** @var \Shift1\Core\View\View $view */
        $view->disableExceptions();
        $view->setViewFile('Libs/Shift1/Core/Resources/Views/exceptionView', false);
        $view->setIsStrict(true);
        $view->assignArray(array(
            'e' => $e,
            'code' => $codeRows,
        ));

        if(\headers_sent()) {
            exit($view->render());
        }

        $header = new Header(500);
        $response = new Response($view->render(), $header);
        $response->sendToClient();

        exit;
    }

}