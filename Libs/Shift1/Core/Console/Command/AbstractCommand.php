<?php
namespace Shift1\Core\Console\Command;

class AbstractCommand {

    protected $parameter;

    public function setParameter(array $parameter) {
        $this->parameter = $parameter;
    }

    public function getParameter($key = null) {
        if(null === $key) {
            return $this->parameter;
        }

        return (empty($this->parameter[$key])) ? false : $this->parameter[$key];

    }

}
