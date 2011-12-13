<?php
namespace Shift1\Log\Writer;

class FileWriter extends AbstractWriter {

    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $format = '%timestamp% %levelName% (%level%): %message%';

    /**
     * @param string $file
     */
    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param string $format
     * @return void
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @return void
     */
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

        \file_put_contents($this->file, \implode(\PHP_EOL, $lines) . \PHP_EOL . \PHP_EOL, FILE_APPEND);
    }

}