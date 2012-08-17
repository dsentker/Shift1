<?php
namespace Shift1\Core\Response\Generator;

use Shift1\Core\InternalFilePath;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Response;

class RedirectGenerator extends AbstractResponseGenerator {

    /**
     * @var string
     */
    protected $target = '';

    /**
     * @var int
     */
    protected $httpStatusCode = 302;

    /**
     * @param string $uri
     * @return self
     */
    public function setTarget($uri) {
        $this->target = $uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param string $uri
     * @return self
     */
    public function setAppTarget($uri) {
        $appUri = $this->getApp()->getConfig()->route->appUri;
        $this->setTarget($appUri . '/' . $uri);
        return $this;
    }

    /**
     * @param int $statusCode
     * @return self
     */
    public function setHttpStatusCode($statusCode) {
        $this->httpStatusCode = (int) $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode() {
        return $this->httpStatusCode;
    }

    /**
     * @return \Shift1\Core\Response\Response
     */
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