<?php
namespace Shift1\Core\Response\Header;

use Shift1\Core\Response\Exceptions\ResponseHeaderException;

abstract class AbstractHeader implements HeaderInterface {

    /**
     * @var array
     */
    protected $headerLines = array();

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @param int $statusCode
     */
    public function __construct($statusCode = 200) {
        $this->statusCode = (int) $statusCode;
    }

    /**
     * @throws ResponseHeaderException
     * @param string $headerString
     * @param bool $overwrite
     * @return void
     */
    public function addLine($headerString, $overwrite = true) {
        if(!\is_string($headerString)) {
            throw new ResponseHeaderException('No valid header string added!', ResponseHeaderException::HEADER_STRING_INALID);
        }
        $this->headerLines[] = array(
          'headerString' => \ucfirst($headerString),
          'overwrite'    => (bool) $overwrite,
        );
    }

    /**
     * @param array $lines
     * @return void
     */
    public function addLines(array $lines) {
        foreach($lines as $line) {
            $this->addLine($line);
        }
    }

    /**
     * @return array
     */
    public function getHeaderLines() {
        return $this->headerLines;
    }

    /**
     * @throws ResponseHeaderException
     * @return void
     */
    public function send() {

        if(\headers_sent($filename, $line)) {
            throw new ResponseHeaderException('Could not output Header data - Headers already sent in ' . $filename . ' (Line ' . $line . ')', ResponseHeaderException::HEADERS_ALREADY_SENT);
        }

        foreach($this->getHeaderLines() as $line) {
            \header($line['headerString'], $line['overwrite'], $this->statusCode);
        }

    }

}