<?php
namespace Shift1\Log\Writer;

use Shift1\Log\iLog;
 
abstract class AbstractWriter implements iLogWriter {

    /**
     * @var int
     */
    protected $priority = iLog::ALL;

    /**
     * @param int $priority
     * @return void
     */
    public function setPriority($priority) {
        $this->priority = (int) $priority;
    }

    /**
     * @return int
     */
    public function getPriority() {
        return $this->priority;
    }


}
