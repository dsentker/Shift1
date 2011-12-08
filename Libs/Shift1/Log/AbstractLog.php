<?php
namespace Shift1\Log;

use Shift1\Core\Exceptions\LoggerException;
 
abstract class AbstractLog implements iLog {

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

    public function getLevelName($levelId, $findClosest = true) {
        $flipped = \array_flip($this->getLevels());
        if(!isset($flipped[$levelId])) {
            if(false === $findClosest) {
                throw new LoggerException('Log level not defined: ' . $levelId);
            }

        }
        return $flipped[$levelId];
    }

    protected function findClosestLevel($levelId) {
        $levels = \ksort(\array_flip(($this->getLevels())), SORT_NUMERIC);
        foreach($levels as $level => $name) {
            if($levelId > $level) {
                /**
                 * @TODO: This is not working properly. Take your time and think about it!
                 */
                return $level;
            }
        }
    }

    public function getLevel($levelName) {
        if(!isset($this->levels[$levelName])) {
            throw new LoggerException('Log level name not defined: ' . $levelName);
        }
        return $this->levels[$levelName];
    }


}
