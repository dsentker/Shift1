<?php
namespace Shift1\Core\Service\Container;
 
class RunningServicesRegistry {

    protected static $services = array();

    /**
     * @static
     * @param string $id
     * @param mixed $service
     * @return void
     */
    public static function add($id, $service) {
        self::$services[$id] = $service;
    }

    /**
     * @static
     * @param string $id
     * @return mixed
     */
    public static function get($id) {
        if(!isset(self::$services[$id])) {
            /** @todo throw exception */
        }
        return self::$services[$id];
    }

    /**
     * @return array
     */
    public static function getAll() {
        return self::$services;
    }

    /**
     * @static
     * @param $id
     * @return bool
     */
    public static function has($id) {
        return isset(self::$services[$id]);
    }

    /**
     * @static
     * @param $id
     * @return bool
     */
    public static function remove($id) {
        if(self::has($id)) {
            unset(self::$services[$id]);
            return true;
        }
        return false;
    }

}
