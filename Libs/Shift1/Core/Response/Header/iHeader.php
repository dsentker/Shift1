<?php
namespace Shift1\Core\Response\Header;

interface iHeader {
    
    public function addLine($headerString);
    public function send();

}

?>
