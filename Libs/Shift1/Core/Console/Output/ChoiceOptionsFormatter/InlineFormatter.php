<?php
namespace Shift1\Core\Console\Output\ChoiceOptionsFormatter;

class InlineFormatter extends AbstractOptionFormatter {

    public function getAvailableOptionsOutput() {

        $optsOut = array();

        foreach($this->getOptions() as $shortcut => $description) {
            $optsOut[] = "{$description} ({$shortcut})";
        }

        if(\count($optsOut) == 1) {
            $out = $optsOut[0];
        } else {
            $lastOption = \array_pop($optsOut);
            $out = \implode(', ', $optsOut) . ' or ' . $lastOption;
        }

        return $out . '?' . \PHP_EOL;

    }



}
