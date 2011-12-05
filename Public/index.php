<?php
/**
 *
 *	SHIFT1 Framework
 *	(C) 2011 by Daniel Sentker
 *	Version: 1.0 (pre-alpha)
 *
 *	Licensed under MIT License
 *      see license.txt for further information
 *
 *      @package SHIFT1 Framework
 *      @author Daniel Sentker
*
 */

use \Application\Boot\Bootstrapper;

define('BASEPATH', realpath(__DIR__ . '/../'));

require \realpath('../Application/Boot/Bootstrapper.php');

Bootstrapper::init();
?>