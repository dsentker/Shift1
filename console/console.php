<?php
use \Application\Boot\Bootstrapper;

require_once \realpath('../Libs/vendor/autoload.php');
require \realpath('../Application/Boot/Bootstrapper.php');

$frontController = \Application\Boot\Bootstrapper::runConsole();
$inputHandler = $frontController->getServiceContainer()->get('shift1.consoleInputHandler');
/** @var $inputHandler \Shift1\Core\Console\InputHandler */
$inputHandler->handle();
