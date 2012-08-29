<?php
namespace Shift1\Core\Debug;

use Shift1\Core\View\View;
use Shift1\Core\View\ViewInterface;
use Shift1\Core\Response\Response;
use Shift1\Core\Response\Header\Header;

class DefaultExceptionHandler extends AbstractExceptionHandler {

    /**
     * @param \Exception $e
     * @throws \Exception
     * @return void
     */
    public function handle(\Exception $e) {
        throw $e;
        exit;
    }

}