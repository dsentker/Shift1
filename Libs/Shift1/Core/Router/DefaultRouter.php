<?php

namespace Shift1\Core\Router;

use Shift1\Core\Router\Route\Route;

class DefaultRouter extends AbstractRouter {

    /**
     * 
     */
    public function __construct() {

        parent::__construct();

        $defaultRoute = new Route('@_controller/@_action', array(
            '_controller' => array(
                'default' => 'Index',
            ),
            '_action' => array(
                'default' => 'index'
            ),

            /*
             * Not used now
             * 

            'lang' => array(
                'default' => 'de',
                'match'   => 'de|en'
            ),
            */

        ));

        $this->addRoute('default', $defaultRoute);
    }

}