<?php
namespace Shift1\Core\Response\Header;

class Header extends AbstractHeader {

    /**
     * @param string $location
     * @return void
     */
    public function setLocation($location) {
        $this->addLine('Location: ' . $location);
    }

    /**
     * @param int $statuscode
     * @return void
     */
    public function setHttpStatusCode($statuscode) {
        $serverProtocol = (!empty($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP\\1.1';
        $this->addLine($serverProtocol . ' ' . $statuscode);
        $this->addLine('Status: ' . $statuscode);
    }

    /*
     * @TODO: Add more methods here
     */

}
