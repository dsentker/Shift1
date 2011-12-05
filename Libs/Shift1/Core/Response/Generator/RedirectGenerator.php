<?php
namespace Shift1\Core\Response\Generator;

use Shift1\Core\Exceptions\ResponseException;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Response;

class RedirectGenerator extends AbstractResponseGenerator {

    protected $target = '';
    protected $httpStatusCode = 302;

    public function setTarget($uri) {
        $this->target = $uri;

        return $this;
    }

    public function getTarget() {
        return $this->target;
    }

    public function setAppTarget($uri) {
        $appUri = $this->getApp()->getConfig()->route->appUri;
        $this->setTarget($appUri . '/' . $uri);

        return $this;
    }

    public function setHttpStatusCode($statusCode) {
        $this->httpStatusCode = (int) $statusCode;

        return $this;
    }

    public function getHttpStatusCode() {
        return $this->httpStatusCode;
    }

    public function getResponse() {

        $header = new Header($this->getHttpStatusCode());
        $header->setLocation($this->getTarget());

        $response = new Response(null, $header);

        $afterSend = function() {
            exit();
        };

        $response->setAfterSend($afterSend);

        return $response;

    }

}