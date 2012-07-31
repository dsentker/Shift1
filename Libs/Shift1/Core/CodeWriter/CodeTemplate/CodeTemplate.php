<?php
namespace Shift1\Core\CodeWriter\CodeTemplate;

use Shift1\Core\VariableSet\VariableSetInterface;
use Shift1\Core\InternalFilePath;

class CodeTemplate implements CodeTemplateInterface {

    /**
     * @var VariableSetInterface
     */
    protected $variableSet;

    /**
     * @var InternalFilePath
     */
    protected $template;

    /**
     * @param VariableSetInterface $variableSet
     */
    public function __construct(VariableSetInterface $variableSet) {
        $this->variableSet = $variableSet;
    }


    /**
     * @param string $templatePath
     */
    public function setTemplateLocation($templatePath) {
        $this->template = new InternalFilePath($templatePath);
    }

    /**
     * @return \Shift1\Core\VariableSet\VariableSetInterface
     */
    public function getVariableSet() {
        return $this->variableSet;
    }

    /**
     * @param string $key
     * @param string $val
     */
    public function add($key, $val) {
        $this->getVariableSet()->add($key, $val);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove($key) {
        return $this->getVariableSet()->remove($key);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function render() {

        if(empty($this->template)) {
            throw new \Exception('Please provide the template Location!');
        }

        $fileContent = \file_get_contents($this->template->getAbsolutePath());

        $vars = array();
        foreach($this->getVariableSet()->getAll() as $key => $val) {
            $vars['/* MARKER_' . $key . ' */'] = $val;
        }

        $rendered = \strtr($fileContent, $vars);

        return $rendered;

    }

}
