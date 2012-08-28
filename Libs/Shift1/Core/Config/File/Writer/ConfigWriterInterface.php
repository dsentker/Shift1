<?php
namespace Shift1\Core\Config\File\Writer;

interface ConfigWriterInterface {

    function setPath($path);

    function write(array $content);

}
