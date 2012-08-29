<?php
namespace Shift1\Core\Routing\Route;

use Shift1\Core\VariableSet\VariableSet;
use Shift1\Core\Config\File\ConfigFileInterface;

class RouteCollection extends VariableSet {

    /**
     * @static
     * @param ConfigFileInterface $config
     * @return RouteCollection
     */
    public static function fromConfig(ConfigFileInterface $config) {

        $collection = new self;
        $routes = $config->toArray();
        foreach($routes as $name => $route) {
            $collection->add($name, $route);
        }
        return $collection;

    }



}
