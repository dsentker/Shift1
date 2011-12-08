<?php
namespace Shift1\Core\Controller;

interface iController {

    public function __construct(array $params = array());

    public function setParams(array $params);

    public function getParams();

    public function init();

}

?>
