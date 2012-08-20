<?php
namespace Shift1\Core\Console\Output\ChoiceOptionsFormatter;

class ListStyleFormatter extends AbstractOptionFormatter {

    public function getAvailableOptionsOutput() {
        $maxShortcutLength = 0;
        $out = '';
        foreach($this->getOptions() as $shortcut => $description) {
            if(($len = \strlen($shortcut)) > $maxShortcutLength) {
                $maxShortcutLength = $len;
            }
        }
        foreach($this->getOptions() as $shortcut => $description) {
            $shortcut = \str_pad($shortcut, $maxShortcutLength, ' ', \STR_PAD_LEFT);
            $out .= \PHP_EOL . ' ' . $shortcut . ' - ' . $description;
        }

        $out .= \PHP_EOL;
        return $out;
    }



}
