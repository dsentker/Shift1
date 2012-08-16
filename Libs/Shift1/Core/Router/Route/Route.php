<?php
namespace Shift1\Core\Router\Route;

use Shift1\Core\Exceptions\RouteException;

class Route implements RouteInterface {

    /**
     * @var string
     */
    protected $scheme = '';

    /**
     * @var array
     */
    protected $bindings = array();

    /**
     * @param string $routeScheme
     * @param array $routeBindings
     */
    public function __construct($routeScheme, array $routeBindings) {
        $this->scheme = $routeScheme;
        $routeBindings = $routeBindings + array('_controller' => array(), '_action' => array());
        $this->bindings = $routeBindings;
    }

    /**
     * @return array An array of given bindings
     */
    public function getBindings() {
        return $this->bindings;
    }

    /**
     * @throws \Shift1\Core\Exceptions\RouteException
     * @param string $bind
     * @return array
     */
    public function getBinding($bind) {
        if(!$this->hasBinding($bind)) {
            throw new RouteException('Route binding "' . $bind . '" does not exist!');
        }
        return $this->bindings[$bind];
    }

    public function hasBinding($bind) {
        return isset($this->bindings[$bind]);
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    public function getSchemeSegments() {
        return \explode(self::URI_SEGMENT_SEPARATOR, \rtrim($this->getScheme(), self::URI_SEGMENT_SEPARATOR));
    }

    /**
     * @return string An regular expression string to match this route with a subject
     */
    public function getSchemeAsPattern() {

        $pattern = array();

        foreach($this->getSchemeSegments() as $position => $segment) {
            if($this->isBindedSegment($segment)) {
                $bind = \str_replace(self::KEYBINDING_CHAR, '', $segment);
                $bindingOpts = $this->getBinding($bind);

                if(!isset($bindingOpts['default'])) {
                    // There is no default value, so this segment is required
                    $match = (isset($bindingOpts['match'])) ? $bindingOpts['match'] : self::DEFAULT_SEGMENT_EXPRESSION;
                    $pattern[] = '(' . $match . ')';
                } else {
                    break;
                }
            } else {
                // This is not a binding, but still needed for the pattern
                $pattern[] = $segment;
            }
        }

        return '^' . \implode(self::URI_SEGMENT_SEPARATOR, $pattern);

    }

    /**
     * @param string $routeSegment
     * @return bool
     */
    public function isBindedSegment($routeSegment) {
        return \strpos($routeSegment, self::KEYBINDING_CHAR) !== false;
    }



}