<?php
namespace Shift1\Core\View\TemplateAnnotationReader;

interface TemplateAnnotationReaderInterface {

    function setTemplate($file);

    function parse();

    function getResult();

    function hasAnnotation($key);

    function hasAnnotationParameter($key);

    function hasAnnotationParameterCount($key, $count, $mode = 'exact');

    function getAnnotationParameter($key);
}
