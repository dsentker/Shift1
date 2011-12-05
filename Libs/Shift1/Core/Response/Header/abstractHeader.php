<?php
namespace Shift1\Core\Response\Header;

use Shift1\Core\Exceptions\ResponseHeaderException;

abstract class AbstractHeader implements iHeader {

    protected $headerLines = array();
    protected $statusCode = 200;

    public function __construct($statusCode = 200) {
        $this->statusCode = (int) $statusCode;
    }

    public function addLine($headerString, $overwrite = true) {
        if(!\is_string($headerString)) {
            throw new ResponseHeaderException('No valid Header string added');
        }
        $this->headerLines[] = array(
          'headerString' => \ucfirst($headerString),
          'overwrite'    => (bool) $overwrite,
        );
    }

    public function addLines(array $lines) {
        foreach($lines as $line) {
            $this->addLine($line);
        }
    }

    public function getHeaderLines() {
        return $this->headerLines;
    }

    public function send() {

        if(\headers_sent($filename, $line)) {
            throw new ResponseHeaderException('Could not output Header data - Headers already sent in ' . $filename . ' (Line ' . $line . ')');
        }

        foreach($this->getHeaderLines() as $line) {
            \header($line['headerString'], $line['overwrite'], $this->statusCode);
        }

    }

}

?>
