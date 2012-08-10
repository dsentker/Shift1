<?php
namespace Shift1\Core\Debug;

use Shift1\Core\View\View;
use Shift1\Core\View\ViewInterface;
use Shift1\Core\Response\Response;
use Shift1\Core\Response\Header\Header;

class HTMLResponseExceptionHandler extends AbstractExceptionHandler {

    const FETCH_LINES_BEFORE = 6;

    const FETCH_LINES_AFTER = 3;

    /**
     * @var \Shift1\Core\View\ViewInterface|\Shift1\Core\View\View
     */
    protected $exceptionView;

    /**
     * @param \Shift1\Core\View\ViewInterface $view
     * @return void
     */
    public function setExceptionView(ViewInterface $view) {
        if($view instanceof View) {
            $view->disableExceptions();
        }
        $this->exceptionView = $view;
    }

    public function getExceptionView() {
        return $this->exceptionView;
    }

    public function handle(\Exception $e) {
        $codeLine = $e->getLine();
        $codeFile = \file($e->getFile());
        $codeRows = array();

        foreach($codeFile as $line => $row) {
            if(($codeLine - self::FETCH_LINES_BEFORE) <= $line && ($codeLine + self::FETCH_LINES_AFTER) >= $line) {
                $codeRows[$line+1] = $row;
            }
        }


        $this->getExceptionView()->assignArray(array(
            'e' => $e,
            'code' => $codeRows,
        ));

        if(\headers_sent()) {
            exit($this->getExceptionView()->render());
        }

        $header = new Header(500);
        $response = new Response($this->getExceptionView()->render(), $header);
        $response->sendToClient();

        exit;
    }

}