<?php
namespace Shift1\Core\Response;

use Shift1\Core\Exceptions\ResponseException;
use Shift1\Core\Response\Header\iHeader;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Generator\RedirectGenerator;
use Shift1\Core\Shift1Object;

abstract class AbstractResponse extends Shift1Object implements iResponse {
    
    protected $header = null;
    protected $content = null;

    /**
     * @var \Closure
     */
    protected $closureAfterSend = null;

    /**
     * @var \Closure
     */
    protected $closureBeforeSend = null;

    public function __construct($content, iHeader $header = null) {
        $this->setContent($content);
        if(!\is_null($header)) $this->setHeader($header);
    }

    public function setContent($content) {
        /*
        if(!\is_string($content) && false === ($content = $this->stringify($content)) ) {
            throw new ResponseException('No valid Response content given');
        }
        */
        $this->content = (string) $content;
    }

    /*
    protected function stringify($data) {
        if(empty($data)) {
            return '';
        } elseif(\is_object($data) && \method_exists($data, '__toString')) {
            return $data->__toString();
        } elseif(\is_array($data)) {
            return (string) $data;
        } else {
            return false;
        }
    }
    */
    
    public function getContent() {
        return $this->content;
    }

    public function setHeader(iHeader $header) {
        $this->header = $header;
    }

    public function getHeader() {
        return $this->header;
    }

    public function getHeaderObject() {
        if($this->getHeader() instanceof iHeader) {
            return $this->getHeader();
        } else {
            return new Header();
        }
    }

    public function setBeforeSend(\Closure $beforeSend) {
        $this->closureBeforeSend = $beforeSend;
    }

    public function getBeforeSend() {
        return $this->closureBeforeSend;
    }

    public function setAfterSend(\Closure $afterSend) {
        $this->closureAfterSend = $afterSend;
    }

    public function getAfterSend() {
        return $this->closureAfterSend;
    }

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


    public static function forceRedirect($to, $statusCode = 302) {

        $response = RedirectGenerator::factory()->setAppTarget($to)->setHttpStatusCode($statusCode)->getResponse();
        $response->sendToClient();


    }
    
}
?>