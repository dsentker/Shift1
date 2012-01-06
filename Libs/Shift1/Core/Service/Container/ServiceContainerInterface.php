<?php
namespace Shift1\Core\Service\Container;

interface ServiceContainerInterface {

    function has($serviceName);

    function get($serviceName);
    
}