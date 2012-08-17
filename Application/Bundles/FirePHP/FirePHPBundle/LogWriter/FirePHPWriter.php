<?php
namespace Bundles\FirePHP\FirePHPBundle\LogWriter;

use Shift1\Log\Event\Event;
use Shift1\Log\Writer\AbstractWriter;

final class FirePHPWriter extends AbstractWriter {

    /**
     * @var \FirePHP
     */
    private $fbInstance;

    public function __construct(\FirePHP $fbInstance) {
        $this->fbInstance = $fbInstance;
    }

    /**
     * @return \FirePHP
     */
    private function getFbInstance() {
        return $this->fbInstance;
    }

    /**
     * @param \Shift1\Log\Event\Event $event
     * @return void
     */
    public function addEvent(Event $event) {
        $fb = $this->getFbInstance();

        switch($errLevel = $event->getErrorLevel()) {
            case ($errLevel >=  80):
                $fb->fb($event->getMessage(), $fb::INFO);
                break;
            case ($errLevel >=  70):
                $fb->fb($event->getMessage(), $fb::LOG);
                break;
            case ($errLevel >=  60):
                $fb->fb($event->getMessage(), $fb::WARN);
                break;
            case ($errLevel >=  0):
                $fb->fb($event->getMessage(), $fb::ERROR);
                break;
            default:
                // Sent the error level name as label
                $fb->fb($event->getMessage(), $event->getErrorLevelName());
                break;
        }
    }

    /**
     * @return void
     */
    public function write() {
        /*
         * Nothing to do here, the FirePHP logs were already
         * sent via ::addEvent().
         */
    }

}