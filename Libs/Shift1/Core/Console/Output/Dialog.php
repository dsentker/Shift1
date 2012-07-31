<?php
namespace Shift1\Core\Console\Output;

class Dialog {

    protected $question;

    protected $answerIsRequired = false;

    protected $answer;

    public function __construct($question) {
        $this->question = $question;
    }

    public function setAnswerIsRequired($state) {
        $this->answerIsRequired = (bool) $state;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function ask() {

        while(!isset($answer)) {
            print new ColorOutput($this->question);
            $input = \trim(\fgets(\STDIN));
            if(!empty($input) || false === $this->answerIsRequired) {
                $answer = $input;
            }
        }

        $this->answer = $answer;

        return $this;
    }

}
