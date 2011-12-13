<?php
namespace Shift1\Core\Response\Generator;

use Shift1\Core\Exceptions\ResponseException;
use Shift1\Core\InternalFilePath;
use Shift1\Core\Response\Header\Header;
use Shift1\Core\Response\Response;

class DownloadableFileGenerator extends AbstractResponseGenerator {

    const DEFAULT_FILE_CONTENTTYPE = 'application/force-download';

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $fileName; // Alias to see in download dialog

    /**
     * @param $filePath
     * @return self
     */
    public function setFile($filePath) {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->filePath;
    }

    /**
     * @param $name
     * @return self
     */
    public function setFileName($name) {
        $this->fileName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName() {
        return (!empty($this->fileName)) ? $this->fileName : \basename($this->getFile());
    }

    /**
     * @throws \Shift1\Core\Exceptions\ResponseException
     * @return \Shift1\Core\Response\Response
     */
    public function getResponse() {

        $fullPath = $this->getFile();

        if(!\file_exists($fullPath)) {
            throw new ResponseException('Downloadable File path does not exist: ' . $fullPath);
        }

        $fileSize = \filesize($fullPath);
        $pathParts = \pathinfo($fullPath);
        $ext = \strtolower($pathParts["extension"]);

        $contentTypesRes = new InternalFilePath('Shift1/Core/Resources/FileContentTypes.php');
        $contentTypes = require_once $contentTypesRes->getAbsolutePath();
        $contentType = (\array_key_exists($ext, $contentTypes)) ? $contentTypes[$ext] : self::DEFAULT_FILE_CONTENTTYPE;

        $headerLines = array(
          'Pragma: Public',
          'Expires: 0',
          'Cache-Control: must-revalidate, post-check=0, pre-check=0',
          'Content-Type: ' . $contentType,
          'Content-Disposition: attachment; filename="' . $this->getFileName() . '";',
          'Content-Transfer-Encoding: binary',
          'Content-Length: ' . $fileSize,
        );

        $header = new Header();
        $header->addLines($headerLines);
        $header->addLine('Cache-Control: private', false); // required for some browsers

        $beforeSend = function() {
            if(\ini_get('zlib.output_compression')) {
                \ini_set('zlib.output_compression', 'Off');
            }
        };

        $afterSend = function() use ($fullPath) {
            \flush();
            \readfile($fullPath);
        };

        $response = new Response(null, $header);
        $response->setBeforeSend($beforeSend);
        $response->setAfterSend($afterSend);
        return $response;

    }

}