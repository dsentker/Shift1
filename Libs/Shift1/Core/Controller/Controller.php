<?php
namespace Shift1\Core\Controller;

use Shift1\Core\Shift1Object;
use Shift1\Core\Exceptions\ControllerException;

abstract class Controller extends Shift1Object implements iController  {

    static $actionDefault = 'index';
    static $actionNotFound = 'notFound';

    protected $params = array();

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function setParams(array $params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    protected function getParam($paramIdentifier, $defaultReturn = null) {
        return ($this->hasParam($paramIdentifier)) ? $this->params[$paramIdentifier] : $defaultReturn;
    }

    protected function hasParam($paramIdentifier) {
        return isset($this->params[$paramIdentifier]);
    }

}

?>
