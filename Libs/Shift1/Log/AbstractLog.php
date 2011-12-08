<?php
namespace Shift1\Log;

use Shift1\Core\Exceptions\LoggerException;
 
abstract class AbstractLog implements iLog {

    protected $levels = array(
        'status' => 256,
        'debug' => 128,
        'info' => 64,
        'warning' => 32,
        'error' => 16,
        'citical' => 8,
    );


    /**
     * @param int $level
     * @param string $slug
     * @return void
     */
    public function addLevel($level, $slug) {
        $this->levels[$slug] = (int) $level;
    }

    /**
     * @return array
     */
    public function getLevels() {
        return $this->levels;
    }

    public function getLevelName($levelId) {
        $flipped = \array_flip($this->getLevels());
        if(!isset($flipped[$levelId])) {
            throw new LoggerException('Level not defined: ' . $levelId);
        }
        return $flipped[$levelId];
    }

    public function getLevel($levelName) {
        return $this->levels[$levelName];
    }


}
