<?php
namespace Shift1\Core\Console\Output;

use Shift1\Core\Console\Output\ChoiceOptionsFormatter\ChoiceOptionsFormatterInterface;
use Shift1\Core\Console\Output\ChoiceOptionsFormatter\InlineFormatter;

class Choice extends Dialog {

    protected $options = array();

    /**
     * @var null|ChoiceOptionsFormatterInterface
     */
    protected $formatter =  null;

    public function __construct($question) {
        parent::__construct($question);
        $this->setAnswerIsRequired(true);
    }

    /**
     * @param ChoiceOptionsFormatterInterface $formatter
     */
    public function setFormatter(ChoiceOptionsFormatterInterface $formatter) {
        $formatter->setOptions($this->getOptions());
        $this->formatter = $formatter;
    }

    /**
     * @return ChoiceOptionsFormatterInterface
     */
    public function getFormatter() {
        if(null === $this->formatter) {
            $formatter = new InlineFormatter();
            $formatter->setOptions($this->getOptions());
            return $formatter;
        } else {
            return $this->formatter;
        }
    }

    public function addOption($shortcut, $description) {
        $this->options[$shortcut] = $description;
        return $this;
    }

    public function getQuestion() {
        $q = parent::getQuestion();
        $q .= $this->getFormatter()->getAvailableOptionsOutput();
        return $q;
    }

    public function getOptions() {
        return $this->options;
    }

    public function checkDialogExit($input) {
        $exitAccepted = parent::checkDialogExit($input);
        return $exitAccepted && \array_key_exists($input, $this->options);
    }







}
