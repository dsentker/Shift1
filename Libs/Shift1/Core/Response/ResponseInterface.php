<?php
namespace Shift1\Core\Response;

use Shift1\Core\Response\Header\HeaderInterface;

interface ResponseInterface {

    /**
     * @abstract
     * @param string $content
     * @return void
     */
    public function setContent($content);

    /**
     * @abstract
     * @return string
     */
    public function getContent();

    /**
     * @abstract
     * @param Header\HeaderInterface $header
     * @return void
     */
    public function setHeader(HeaderInterface $header);

    /**
     * @abstract
     * @return Header\iHeader
     */
    public function getHeader();

    /**
     * @abstract
     * @return void
     */
    public function sendToClient();

    /**
     * @static
     * @abstract
     * @param string $to
     * @param int $response
     * @return void
     */
    public static function forceRedirect($to, $response = 302);
    
}