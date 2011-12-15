<?php
namespace Shift1\Core\Controller;

use Shift1\Core\View\View;

class Controller extends AbstractController {

    /**
     * @var \Shift1\Core\View\View
     */
    protected $view;

    /**
     * @param array $params
     */
    final public function __construct(array $params = array()) {
        parent::__construct($params);
        /**
         * @TODO GET THIS WORK
         * Die View muss immer dem reellen Controllernamen \DS Actionnamen entsprechen.
         * Momentan keine Möglichkeit da, um diesen Namen herauszufinden.
         *
         * Mögliche Lösung: Klasse zum Transformieren des Namens des Controllers zur
         * rellen Controllerklasse. => DI ???
         *
         * Außerdem: Redundanz und fehlende Abstraktion zwischen Dispatcher und FrontController beheben.
         */
        $this->view = new View($this->getControllerName() . '/' . $params['_action']);
        $this->init();
    }

    /**
     * @return \Shift1\Core\View\View
     */
    public function getView() {
        return $this->view;
    }

}