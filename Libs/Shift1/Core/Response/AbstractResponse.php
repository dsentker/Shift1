<?php
namespace Shift1\Core\Response;

use Shift1\Core\Exceptions\ResponseException;
use Shift1\Core\Response\Header\iHeader;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Generator\RedirectGenerator;
use Shift1\Core\Shift1Object;

abstract class AbstractResponse extends Shift1Object implements iResponse {

    /**
     * @var null|Header\iHeader
     */
    protected $header = null;

    /**
     * @var null|string
     */
    protected $content = null;

    /**
     * @var \Closure
     */
    protected $closureAfterSend = null;

    /**
     * @var \Closure
     */
    protected $closureBeforeSend = null;

    /**
     * @param string $content
     * @param null|Header\iHeader $header
     */
    public function __construct($content, iHeader $header = null) {
        $this->setContent($content);
        if(!\is_null($header)) $this->setHeader($header);
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content) {
        $this->content = (string) $content;
    }

    /**
     * @return string
     */
    public function getContent() {
        return (string) $this->content;
    }

    /**
     * @param Header\iHeader $header
     * @return void
     */
    public function setHeader(iHeader $header) {
        $this->header = $header;
    }

    /**
     * @return null|Header\iHeader
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @return null|Header\Header|Header\iHeader
     */
    public function getHeaderObject() {
        if($this->getHeader() instanceof iHeader) {
            return $this->getHeader();
        } else {
            return new Header();
        }
    }

    /**
     * @param \Closure $beforeSend
     * @return void
     */
    public function setBeforeSend(\Closure $beforeSend) {
        $this->closureBeforeSend = $beforeSend;
    }

    /**
     * @return \Closure|null
     */
    public function getBeforeSend() {
        return $this->closureBeforeSend;
    }

    /**
     * @param \Closure $afterSend
     * @return void
     */
    public function setAfterSend(\Closure $afterSend) {
        $this->closureAfterSend = $afterSend;
    }

    /**
     * @return \Closure|null
     */
    public function getAfterSend() {
        return $this->closureAfterSend;
    }

    /**
     * @return void
     */
    public function sendToClient() {

        if(null !== $this->getBeforeSend()) {
            $beforeSend = $this->getBeforeSend();
            $beforeSend();
        }

        $this->getHeaderObject()->send();
        echo $this->getContent();

        if(null !== $this->getAfterSend()) {
            $afterSend = $this->getAfterSend();
            $afterSend();
        }
    }


    /**
     * @static
     * @param $to
     * @param int $statusCode
     * @return void
     */
    public static function forceRedirect($to, $statusCode = 302) {
        $response = RedirectGenerator::factory()->setAppTarget($to)->setHttpStatusCode($statusCode)->getResponse();
        $response->sendToClient();
    }
    
}