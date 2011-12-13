<?php
namespace Shift1\Core\Debug;

use Shift1\Core\View\View;
use Shift1\Core\Response;

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

        $view = new View('exceptionView');
        $view->setViewPath('Libs/Shift1/Core/Resources/Views/');
        $view->assignArray(array(
                'e' => $e,
                'code' => $codeRows,
        ));

        $header = new Response\Header\Header(500);
        $response = new Response\Response($view->render(), $header);
        $response->sendToClient();

        return false;
    }
}