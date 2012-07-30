<?php
namespace Shift1\Core\Console;

class InputHandler {

    public function handle() {

        $args = $this->parseArguments();

        echo print_r($args);exit;

        $handler = $this->getCommandHandler($args['command']);
        $handler->setParameter($args['params']);
        $handler->execute();






    }

    protected function hasCommandHandler($command) {
        return \class_exists(__NAMESPACE__ . '\Command\\' . \ucfirst($command) . 'Command');
    }

    protected function getCommandHandler($command) {

        if($this->hasCommandHandler($command)) {
            $class = __NAMESPACE__ . '\Command\\' . \ucfirst($command) . 'Command';
            return new $class;
        } else {
            // not exist
        }



    }

    /**
     * Parses $GLOBALS['argv'] for parameters and assigns them to an array.
     *
     * Supports:
     * -e
     * -e <value>
     * --long-param
     * --long-param=<value>
     * --long-param <value>
     * <value>
     *
     */
    protected function parseArguments() {

        $result = array();
        $params = $GLOBALS['argv'];
        unset($params[0]);

        while (list($i, $p) = each($params)) {
            if ($p{0} == '-') {
                $pname = substr($p, 1);
                $value = true;
                if ($pname{0} == '-') {
                    // long-opt (--<param>)
                    $pname = \substr($pname, 1);
                    if (\strpos($p, '=') !== false) {
                        // value specified inline (--<param>=<value>)
                        list($pname, $value) = \explode('=', \substr($p, 2), 2);
                    }
                }
                // check if next parameter is a descriptor or a value
                $nextparm = current($params);
                if ($value === true && $nextparm !== false && $nextparm{0} != '-') list($tmp, $value) = each($params);
                $result[$pname] = $value;
            } else {
                // param doesn't belong to any option
                $result[] = $p;
            }
        }

        $command = \array_shift($result);
        return array(
            'command' => $command,
            'params'  => $result,
        );


    }
}
