<?php
use \Application\Boot\Bootstrapper;

require_once \realpath('../Libs/vendor/autoload.php');
require \realpath('../Application/Boot/Bootstrapper.php');
Bootstrapper::runStaging();