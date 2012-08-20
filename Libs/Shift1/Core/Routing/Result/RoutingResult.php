<?php
namespace Shift1\Core\Routing\Result;

use Shift1\Core\VariableSet\VariableSet;
use Shift1\Core\Routing\Route\RouteInterface;
use Shift1\Core\Routing\ParamConverter\Factory\ParamConverterFactory;

class RoutingResult extends VariableSet {

    /**
     * @return RouteInterface|null
     */
    public function getRoute() {
        return $this->get('_route');
    }

    public function setRoute(RouteInterface $route) {
        $this->add('_route', $route);
    }

    public function convertParams(ParamConverterFactory $factory) {
        $opts = $this->getRoute()->getParamOptions();
        foreach($this->getVars() as $paramKey => $paramValue) {
            if(\is_string($paramValue)) {
                if(isset($opts['@' . $paramKey]['paramConverter'])) {
                    $converter = $factory->createConverter($opts['@' . $paramKey]['paramConverter']);
                    /** @var $converter \Shift1\Core\Routing\ParamConverter\AbstractParamConverter */
                    $this->modify($paramKey, $converter->getActionParam($paramValue));
                }
            }
        }

    }



}
