<?php
namespace Shift1\Core\Bundle\Definition;

use Shift1\Core\Bundle\Exceptions\DefinitionException;
use Shift1\Core\InternalFilePath;

class TemplateDefinition extends BundleDefinition {

    const TEMPLATE_EXT = '.tpl.php';

    /**
     * @var string
     */
    protected $templateDefinition;

    /**
     * @var mixed|string
     */
    protected $templateFile;

    /**
     * @param string $definition like vendor:bundleName:folder/templateFile
     * @throws DefinitionException if the definition scheme was not valid
     */
    public function __construct($definition) {

        $parts = \explode(':', $definition);
        if(!isset($parts[2]) || isset($parts[3])) {
            throw new DefinitionException("A template definition must have a scheme like 'vendor:bundle:template', '{$definition}' given!", DefinitionException::TEMPLATE_DEFINITION_INVALID);
        }

        $templateFile = \array_pop($parts);

        if(false !== \strpos($templateFile, '/') || false !== \strpos($templateFile, '\\')) {
            $templateFile = \ucfirst($templateFile);
        }

        $this->templateDefinition = $definition;
        $this->templateFile = $templateFile;
        $bundleDefinition = \implode(':', $parts);

        parent::__construct($bundleDefinition);
    }

    /**
     * @return string
     */
    public function getTemplateDefinition() {
        return $this->templateDefinition;
    }

    /**
     * @param bool $withExtension
     * @return string
     */
    public function getTemplateFile($withExtension = true) {
        return $withExtension ? $this->templateFile . self::TEMPLATE_EXT : $this->templateFile;
    }

    /**
     * @param string $subFolder
     * @return \Shift1\Core\InternalFilePath
     */
    public function getTemplateFilePath($subFolder = '') {
        if(!empty($subFolder)) {
            $subFolder .= '/';
        }
        $relative = 'Application/Bundles/' . $this->getVendorName() . '/' . $this->getBundleName() . '/Views/' . $subFolder . $this->getTemplateFile(true);
        return new InternalFilePath($relative);
    }}
