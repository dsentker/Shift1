<?php
namespace Shift1\Core\Bundle\Converger;

use Shift1\Core\Routing\Route\RouteCollection;

class RouteConverger extends BundleConverger implements RouteConvergerInterface {

    protected function getBundleHttpRouteCollection(RouteCollection $collection) {

        /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
        foreach($this->getBundleManager() as $bundleManager) {
            $collection->merge($bundleManager->loadHttpRouteCollection());
        }

        return $collection;

    }

    protected function getBundleConsoleRouteCollection(RouteCollection $collection) {

        /** @var $bundleManager \Shift1\Core\Bundle\Manager\BundleManagerInterface */
        foreach($this->getBundleManager() as $bundleManager) {
            $collection->merge($bundleManager->loadConsoleRouteCollection());
        }

        return $collection;
    }

    public function getBundleRouteCollection($type = self::ROUTES_HTTP, RouteCollection $collection = null) {

        $routeCollection = (null === $collection) ? new RouteCollection() : $collection;

        switch($type) {
            case self::ROUTES_HTTP:
                return $this->getBundleHttpRouteCollection($routeCollection);
            case self::ROUTES_CLI:
                return $this->getBundleConsoleRouteCollection($routeCollection);
            default:
                die('wrong type'); /** @todo throw exception */
        }
    }

}