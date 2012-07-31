<?php
namespace Shift1\Core\CodeWriter;

use Shift1\Core\CodeWriter\CodeTemplate\CodeTemplateInterface;

class MethodWriter {

    /**
     * @var string
     */
    protected $classNamespace = '';

    protected $processedContent = null;

    /**
     * @param string $classNamespace
     */
    public function __construct($classNamespace) {
        $this->classNamespace = (string) $classNamespace;
    }

    /**
     * @param CodeTemplateInterface $codeTemplate
     * @param string $markerName
     */
    public function injectCode(CodeTemplateInterface $codeTemplate, $markerName = 'APPEND') {

        if(null === $this->processedContent) {
            $classReflection = new \ReflectionClass($this->classNamespace);
            $this->processedContent = \file_get_contents($classReflection->getFileName());
        }

        $this->processedContent = \str_replace('/* MARKER_' . $markerName . ' */', $codeTemplate->render(), $this->processedContent);

    }

    /**
     * @return null
     */
    public function getProcessedContent() {
        return $this->processedContent;
    }

    public function persist() {
        $classReflection = new \ReflectionClass($this->classNamespace);
        \file_put_contents($classReflection->getFileName(), $this->processedContent);
    }

}
