<?php
namespace Shift1\Core\Exceptions;

class FileNotFoundException extends \Exception {
    
    public function __construct($filePath) {
        parent::__construct('File not found: ' . $filePath);
    }
    
}
?>
