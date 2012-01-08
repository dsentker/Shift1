<?php
namespace Shift1\Core\View\Renderer;
 
abstract class AbstractRenderer implements RendererInterface {

    /**
     * @var array
     */
    protected $vars = array();

    /**
     * @var string
     */
    protected $file;

    /**
     * @param array $vars
     * @return void
     */
    public function setVars(array $vars) {
        $this->vars = $vars;
    }

    /**
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }

    /**
     * @param string $tplFile
     * @return void
     */
    public function setTemplate($tplFile) {
        $this->file = $tplFile;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->file;
    }

}