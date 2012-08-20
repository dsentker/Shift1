<?php
namespace Shift1\Core\Console\Output\ChoiceOptionsFormatter;

interface ChoiceOptionsFormatterInterface {

    function setOptions(array $options);

    function getAvailableOptionsOutput();

}
