<?php
namespace Shift1\Log\Writer;


class FileWriter extends AbstractWriter {

    protected $file;

    protected $format = '%timestamp% %levelName% (%level%): %message%';

    public function __construct($file) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getFormat() {
        return $this->format;
    }

    public function write() {

        $lines = array();

        foreach($this->getEvents() as $event) {
            /** @var \Shift1\Log\Event\Event $event */
            $translate = array(
                '%timestamp%' => $event->getTimestamp(),
                '%levelName%' => $event->getErrorLevelName(),
                '%level%'     => $event->getErrorLevel(),
                '%message%'   => $event->getMessage(),
            );
            $lines[] = \strtr($this->getFormat(), $translate);
        }
        
        \file_put_contents($this->file, \implode($lines) . \PHP_EOL . \PHP_EOL, FILE_APPEND);
    }

}
