<?php
namespace Shift1\Core\Response;

use Shift1\Core\Response\Header\HeaderInterface;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Generator\RedirectGenerator;
use Shift1\Core\View\ViewInterface;

abstract class AbstractResponse implements ResponseInterface {

    /**
     * @var null|Header\HeaderInterface
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
     * @param null|Header\HeaderInterface $header
     */
    public function __construct($content, HeaderInterface $header = null) {
        $this->setContent($content);
        if(!\is_null($header)) $this->setHeader($header);
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return string|\Shift1\Core\View\ViewInterface
     */
    public function getContent() {

        return $this->content;

    }

    /**
     * @param Header\HeaderInterface $header
     * @return void
     */
    public function setHeader(HeaderInterface $header) {
        $this->header = $header;
    }

    /**
     * @return null|Header\HeaderInterface
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @return null|Header\HeaderInterface
     */
    public function getHeaderObject() {
        if($this->getHeader() instanceof HeaderInterface) {
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

        $content = $this->getContent();

        if($content instanceof Renderable) {
            $content = $content->render();
        }

        echo $content;

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