<?php
namespace Shift1\Core\Response\Header;

interface iHeader {

    /**
     * @abstract
     * @param string $headerString
     * @return void
     */
    public function addLine($headerString);

    /**
     * @abstract
     * @return void
     */
    public function send();

}
