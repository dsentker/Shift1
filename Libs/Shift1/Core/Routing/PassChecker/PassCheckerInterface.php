<?php
namespace Shift1\Core\Routing\PassChecker;

interface PassCheckerInterface {

    function __construct();

    function isValid(array $data);

}
