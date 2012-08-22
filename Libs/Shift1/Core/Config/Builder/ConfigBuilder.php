<?php
namespace Shift1\Core\Config\Builder;

use Shift1\Core\Config\Builder\Item\ConfigItem;
use Shift1\Core\Config\Exceptions\BuilderException;

class ConfigBuilder {

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
     * @var null|\Closure
     */
    protected $addItemPreCallback = null;

    /**
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $this->config = $config;
    }

    /**
     * Sets a closure which will be called before a config item is added to the builded config array via ::adItem().
     * This closure will be called with two parameters: First, the item, second, the current node path.
     * Note that the closure has to return a boolean true to proceed; otherwise there will be a self-recursive
     * call to ::addItem().
     *
     * @param \Closure $callback The closure which will be called if a new config item is set.
     */
    public function setAddItemPreCallback(\Closure $callback)  {
        $this->addItemPreCallback = $callback;
    }

    /**
     * @return \Closure
     */
    public function getAddItemPreCallback()  {
        if(null === $this->addItemPreCallback) {
            return function() { return true; };
        } else {
            return $this->addItemPreCallback;
        }
    }

    /**
     * @return string
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
        $this->currentNode .= self::NODEPATH_SEPARATOR . $nodePath;
        return $this;
    }

    /**
     * @param $nodePath
     * @return ConfigBuilder
     */
    public function getNode($nodePath) {
        $this->currentNode = $nodePath;
        return $this;
    }

    /**
     * Sets a new item to the builded config. Before integration, a function will be called, if
     * set via ::setAddItemPreCallback()
     *
     * @param Item\ConfigItem $item
     * @return ConfigBuilder
     */
    public function addItem(ConfigItem $item) {

        $callback = $this->getAddItemPreCallback();
        $callbackFinished = $callback($item, $this->getCurrentNode());

        if(!$callbackFinished)  {
            return $this->addItem($item);
        }

        $this->setNodes(array($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $item->getKey() => $item->getValue() ), $this->config);
        return $this;
    }

    /**
     * @return mixed
     */
    public function dump() {
        return print_r($this->config, true);
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
