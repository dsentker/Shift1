<?php
namespace Shift1\Core\Config\Builder;

use Shift1\Core\Config\Builder\Item\ConfigItem;
use Shift1\Core\Config\Exceptions\BuilderException;

class ConfigTreeBuilder {

    const NODEPATH_SEPARATOR = '.';

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var string
     */
    protected $currentNode = '';

    /**
     * @var array
     */
    protected $processedNodes = array();

    protected $nodeCollisionHandler = null;

    /**
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $this->config = $config;
    }

    public function setNodeCollisionHandler($nodeCollisionHandler)  {
        $this->nodeCollisionHandler = $nodeCollisionHandler;
    }

    public function getNodeCollisionHandler() {
        return $this->nodeCollisionHandler;
    }

    /**
     * @return string the current node, whitespace-trimmed
     */
    public function getCurrentNode() {
        return \trim($this->currentNode, self::NODEPATH_SEPARATOR);
    }

    /**
     * @param string $nodePath
     * @return ConfigBuilder
     */
    public function addNode($nodePath) {
        $this->setNodes(array($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $nodePath => array()), $this->config);
        //$this->currentNode .= self::NODEPATH_SEPARATOR . $nodePath;
        return $this;
    }

    /**
     * Sets the current node path to use this path for later actions. A single dot (.) will set the
     * node path to root.
     * @param $nodePath
     * @return ConfigBuilder
     */
    public function node($nodePath) {
        $this->currentNode = ('.' === $nodePath) ? '' :  $nodePath;
        return $this;
    }

    /**
     * Sets a new item to the builded config. Before integration, a function will be called, if
     * set via ::setAddItemPreCallback()
     *
     * @param Item\ConfigItem $item
     * @return ConfigBuilder
     */
    public function addItem($key, $value = '') {

        $this->setNodes(array($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $key => $value ), $this->config);
        return $this;
    }

    /**
     * @return mixed
     */
    public function dump() {
        return print_r($this->config, true);
    }

    /**
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Sets key/value pairs at any depth on an array.
     * Thanks to cyranix at cyranix dot com via php.net/manual/de/function.array-walk-recursive.php#106340 - slightly modified
     * If a key in the $data-array is a string like foo.bar.baz, the value
     * will injected in foo => array( bar => array( baz => array( [here] )))
     *
     * @param array $data an array of key/value pairs to be added/modified
     * @param array $array the array to operate on
     */
    protected function setNodes(array $data, &$array) {
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
