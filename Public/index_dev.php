<?php
use \Application\Kernel\Bootstrapper;

require_once \realpath('../Application/Kernel/Bootstrapper.php');
$fc = Bootstrapper::getFrontController('dev');
$fc->getServiceContainer()->get('log')->registerErrorHandler(false); // hide me if u got problems
$fc->executeHttp();