<?php
namespace Application\Events;

use Symfony\Component\EventDispatcher\Event;
use Shift1\Core\View\ViewInterface;

class ViewEvent extends Event {

    /**
     * @var \Shift1\Core\View\ViewInterface
     */
    public $view;

    public function __construct(ViewInterface $view) {
        $this->view = $view;
    }

}
