<?php
namespace Shift1\Core\Request;

use Shift1\Core\FrontController;

class InternalRequest extends AbstractRequest implements InternalRequestInterface {

    /**
     * @static
     * @param string $requestUri The internal uri
     * @return \Shift1\Core\Request\InternalRequestInterface
     */
    public static function generate($requestUri) {
        
        $current = FrontController::getInstance()->getRequest();

        if($current instanceof RequestInterface) {
            $newInternal = clone $current;
            $newInternal->setIsInternal(true);
            $newInternal->setRequestUri($requestUri);
        } else {
            $newInternal = new static($requestUri);
        }

        $newInternal->setUserAgent('SHIFT1');
        return $newInternal;
    }

     /**
      * Note that ::generate is a better way to
      * initiate a new internal request
      *
      * @see InternalRequest::generate
      * @param string $requestUri The internal uri
      */
     public function __construct($requestUri) {
         parent::__construct($requestUri);
         $this->setIsInternal(true);
     }

}