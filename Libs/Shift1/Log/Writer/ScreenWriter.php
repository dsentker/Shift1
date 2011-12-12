<?php
namespace Shift1\Log\Writer;

class ScreenWriter extends AbstractWriter {

    protected $format = '<div style="border: 1px solid #ff9900; background-color: #fff1c8; padding: 10px;">%levelName%:<br />%message%</div>';

    public function setFormat($format) {
        $this->format = $format;
    }

    public function getFormat() {
        return $this->format;
    }

    public function write() {

        $boxes = array();

        foreach($this->getEvents() as $event) {
            /** @var \Shift1\Log\Event\Event $event */
            $translate = array(
                '%timestamp%' => $event->getTimestamp(),
                '%levelName%' => $event->getErrorLevelName(),
                '%level%'     => $event->getErrorLevel(),
                '%message%'   => $event->getMessage(),
            );
            $boxes[] = \strtr($this->getFormat(), $translate);
        }

        echo \implode('<br />', $boxes);
    }

}
