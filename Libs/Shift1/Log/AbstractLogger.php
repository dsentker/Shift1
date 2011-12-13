<?php
namespace Shift1\Log;

use Shift1\Core\Exceptions\LoggerException;
 
abstract class AbstractLogger implements iLogger {

    /**
     * @var array
     */
    protected $levels = array(
        'debug'  => 90,
        'info'   => 80,
        'notice' => 70,
        'warn'   => 60,
        'err'    => 50,
        'crit'   => 40,
        'alert'  => 30,
        'emerg'  => 20,
    );

    /**
     * @param int $level
     * @param string $name
     * @return void
     */
    public function addLevel($level, $name) {
        $name = strtolower($name);
        $level = (int) $level;

        if (isset($this->levels[$name]) || false !== array_search($level, $this->levels)) {
            throw new LoggerException('Existing levels cannot be overwritten!');
        }
        $this->levels[$name] = $level;
    }

    /**
     * @return array
     */
    public function getLevels() {
        return $this->levels;
    }

    /**
     * @throws \Shift1\Core\Exceptions\LoggerException
     * @param string $levelId
     * @return string
     */
    public function getLevelName($levelId) {
        $flipped = \array_flip($this->getLevels());
        if(!isset($flipped[$levelId])) {
            throw new LoggerException('Log level not defined: ' . $levelId);
        }
        return $flipped[$levelId];
    }

    /**
     * @throws \Shift1\Core\Exceptions\LoggerException
     * @param string $levelName
     * @return string
     */
    public function getLevel($levelName) {
        if(!isset($this->levels[$levelName])) {
            throw new LoggerException('Log level name not defined: ' . $levelName);
        }
        return $this->levels[$levelName];
    }

}