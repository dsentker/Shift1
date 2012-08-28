<?php
use \Application\Kernel\Bootstrapper;

require \realpath('../Application/Kernel/Bootstrapper.php');
$fc = Bootstrapper::getFrontController('live');
$fc->executeHttp();