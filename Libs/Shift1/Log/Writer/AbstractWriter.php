<?php
namespace Shift1\Log\Writer;

use Shift1\Log\iLog;
 
abstract class AbstractWriter implements iLogWriter {

    /**
     * @var int
     */
    protected $level = 'debug';

    /**
     * @param int $priority
     * @return void
     */
    public function setLevel($priority) {
        $this->level = (int) $priority;
    }

    /**
     * @return string
     */
    public function getLevel() {
        return $this->level;
    }


}
