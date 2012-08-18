<?php
namespace Shift1\Core\Routing\PassChecker;

use Shift1\Core\Routing\Result\RoutingResult;

interface PassCheckerInterface {

    function __construct();

    function isValid(RoutingResult $result);

}
