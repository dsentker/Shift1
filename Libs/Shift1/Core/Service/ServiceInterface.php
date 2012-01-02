<?php
namespace Shift1\Core\Service;

interface ServiceInterface {

    /**
     * @abstract
     * @return object
     */
    function getInstance();
    
}