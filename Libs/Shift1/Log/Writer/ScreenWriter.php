<?php
namespace Shift1\Log\Writer;

class ScreenWriter extends AbstractWriter {

    public function write() {
        foreach($this->getEvents() as $event) {
            /** @var \Shift1\Log\Event\Event $event */
            $event->__toString();
        }
    }

}
