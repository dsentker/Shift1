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

    public function dialog($prompt, array $valid_inputs = array(), $default = '') {
        while(!empty($valid_inputs) || !isset($input) || !in_array($input, $valid_inputs)) {
            echo $prompt . \PHP_EOL;
            $input = strtolower(trim(fgets(\STDIN)));
            if(empty($input) && !empty($default)) {
                $input = $default;
            }
        }
        return $input;
    }

}
