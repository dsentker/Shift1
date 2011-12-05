<?php
namespace Some\Name\Space;

class TestClass {
    
    protected $outputString = 'Hello World';
    
    public function setString($outputString = '') {
        $this->outputString = $outputString;
        
    }
    
    public function run() {
        return $this->outputString;
    }
    
}

?>
