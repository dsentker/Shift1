<?php
namespace Shift1\Log\Writer;

use Shift1\Core\Exceptions\LoggerException;
use Shift1\Core\FrontController;
use Shift1\Log\Event\Event;

final class FirePHPWriter extends AbstractWriter {

    /**
     * @var \FirePHP
     */
    private $fbInstance;

    public function __construct() {
        $serviceContainer = FrontController::getInstance()->getServiceContainer();
        if(!$serviceContainer->has('FirePHP')) {
            throw new LoggerException('Can\'t use FirePHPWriter: Service "FirePHP" not found!');
        }
        $this->fbInstance = $serviceContainer->get('FirePHP');
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
         * Nothing to do here, the
         * firephp logs were already
         * sent via ::addEvent().
         */
    }

}