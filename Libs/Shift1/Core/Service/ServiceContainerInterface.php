<?php
namespace Shift1\Core\Service;

interface ServiceContainerInterface {

    function has($serviceName);

    function get($serviceName);
    
}