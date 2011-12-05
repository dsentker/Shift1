<?php
namespace Shift1\Core\Response\Header;

class Header extends AbstractHeader {

    public function setLocation($location) {
        $this->addLine('Location: ' . $location);
    }

    public function setHttpStatusCode($statuscode) {
        $serverProtocol = (!empty($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP\\1.1';
        $this->addLine($serverProtocol . ' ' . $statuscode);
        $this->addLine('Status: ' . $statuscode);
    }

    /**
     * @todo: Add more methods here
     */

}
?>
