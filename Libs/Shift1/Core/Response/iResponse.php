<?php
namespace Shift1\Core\Response;

use Shift1\Core\Response\Header\iHeader;

interface iResponse {
    
    public function setContent($content);
    public function getContent();

    public function setHeader(iHeader $header);
    public function getHeader();

    public function sendToClient();

    public static function forceRedirect($to, $response = 302);
    
    
}