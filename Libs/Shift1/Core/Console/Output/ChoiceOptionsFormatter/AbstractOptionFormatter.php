<?php
namespace Shift1\Core\Console\Output\ChoiceOptionsFormatter;

abstract class AbstractOptionFormatter implements ChoiceOptionsFormatterInterface {

    protected $options = array();

    /**
     * @param array $options
     */
    public function setOptions(array $options) {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

}
