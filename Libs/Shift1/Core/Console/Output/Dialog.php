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

    public function getQuestion() {
        return $this->question;
    }

    public function ask() {

        while(!isset($answer)) {
            print new ColorOutput($this->getQuestion());
            $input = \trim(\fgets(\STDIN));
            if($this->checkDialogExit($input)) {
                $answer = $input;
            }
        }

        $this->answer = $answer;

        return $this;
    }

    /**
     * @param $input
     * @return bool true if the dialog is finished.
     */
    protected function checkDialogExit($input) {
        return !empty($input) || false === $this->answerIsRequired;
    }

}
