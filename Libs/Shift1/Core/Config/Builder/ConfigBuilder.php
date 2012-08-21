<?php
namespace Shift1\Core\Config\Builder;

use Shift1\Core\Config\Builder\Item\ConfigItem;

use Shift1\Core\Console\Output\Dialog;
 
class ConfigBuilder {

    const NODEPATH_SEPARATOR = '.';

    /**
     * @var array
     */
    protected $config = array();

    protected $currentNode = '';

    public function __construct(array $config = array()) {
        $this->config = $config;
    }

    public function getCurrentNode() {
        return \trim($this->currentNode, self::NODEPATH_SEPARATOR);
    }

    public function addNode($nodePath) {
        $this->setNodes(array($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $nodePath => array()), $this->config);
        $this->currentNode .= self::NODEPATH_SEPARATOR . $nodePath;
        return $this;
    }

    public function getNode($nodePath) {
        $this->currentNode = $nodePath;
        return $this;
    }

    public function addItem(ConfigItem $item) {

        if($item->getNeedValueInput()) {
            /** @todo this is not very pretty! */
            $dialog = new Dialog($item->getPrompt());
            $answer = $dialog->ask()->getAnswer();
            $item->setValue($answer);
        }

        $this->setNodes(array($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $item->getKey() => $item->getValue() ), $this->config);
        return $this;
    }

    public function dump() {
        return print_r($this->config, true);
    }

    /**
     * Sets key/value pairs at any depth on an array.
     * Thanks to cyranix at cyranix dot com via php.net/manual/de/function.array-walk-recursive.php#106340 - slightly modified
     *
     * @param array $data an array of key/value pairs to be added/modified
     * @param array $array the array to operate on
     */
    public function setNodes(array $data, &$array) {
        foreach ($data as $name => $value) {
            if (\strpos($name, self::NODEPATH_SEPARATOR) === false) {
                // If the array doesn't contain a special separator character, just set the key/value pair.
                // If $value is an array, you will of course set nested key/value pairs just fine.
                $array[$name] = $value;
            } else {
                // In this case we're trying to target a specific nested node without overwriting any other siblings/ancestors.
                // The node or its ancestors may not exist yet.
                $keys = \explode(self::NODEPATH_SEPARATOR, $name);
                // Set the root of the tree.
                $opt_tree =& $array;
                // Start traversing the tree using the specified keys.
                while ($key = \array_shift($keys)) {
                    // If there are more keys after the current one...
                    if ($keys) {
                        if (!isset($opt_tree[$key]) || !\is_array($opt_tree[$key])) {
                            // Create this node if it doesn't already exist.
                            $opt_tree[$key] = array();
                        }
                        // Redefine the "root" of the tree to this node (assign by reference) then process the next key.
                        $opt_tree =& $opt_tree[$key];
                    } else {
                        // This is the last key to check, so assign the value.
                        $opt_tree[$key] = $value;
                    }
                }
            }
        }
    }



}
