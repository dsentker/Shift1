<?php
namespace Shift1\Log\Writer;

use Shift1\Log\Event\Event;
use Shift1\Core\Shift1Object;

abstract class AbstractWriter extends Shift1Object implements iLogWriter {

    /**
     * @var string
     */
    protected $level = 'debug';

    /**
     * @var array
     */
    protected $events = array();

    /**
     * @param \Shift1\Log\Event\Event $event
     * @return self
     */
    public function addEvent(Event $event) {
        $this->events[] = $event;
        return $this;
    }

    /**
     * @return array
     */
    public function getEvents() {
        return $this->events;
    }

    /**
     * @param int $priority
     * @return void
     */
    public function setLevel($priority) {
        $this->level = $priority;
    }

    /**
     * @return string
     */
    public function getLevel() {
        return $this->level;
    }


}
