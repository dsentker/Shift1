<?php
use \Application\Kernel\Bootstrapper;

require_once \realpath('../Application/Kernel/Bootstrapper.php');
$fc = Bootstrapper::getFrontController('console');
$fc->executeConsole();