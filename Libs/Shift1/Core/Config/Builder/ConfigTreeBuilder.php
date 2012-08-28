<?php
namespace Shift1\Core\Config\Builder;

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
    protected $adjustmentRequests = array();


    /**
     * @param array $config
     */
    public function __construct(array $config = array()) {
        $this->config = $config;
    }

    /**
     * @return string the current node, separator-trimmed
     */
    public function getCurrentNode() {
        return \trim($this->currentNode, self::NODEPATH_SEPARATOR);
    }

    /**
     * Sets the current node path to use this path for later actions. A single dot (.) will set the
     * node path to root.
     * @param $nodePath
     * @return ConfigTreeBuilder
     */
    public function node($nodePath) {
        $this->currentNode = ('.' === $nodePath) ? '' :  $nodePath;
        return $this;
    }

    /**
     * @param AdjustmentRequest $adjustmentRequest
     * @return ConfigTreeBuilder
     */
    public function addAdjustmentRequest(AdjustmentRequest $adjustmentRequest) {
        $this->adjustmentRequests[$adjustmentRequest->getSubject()] = $adjustmentRequest;
        return $this;
    }

    /**
     * @param array $adjustmentRequests
     * @return ConfigTreeBuilder
     */
    public function addAdjustmentRequests(array $adjustmentRequests) {
        foreach($adjustmentRequests as $req) {
            $this->addAdjustmentRequest($req);
        }
        return $this;
    }

    /**
     * Sets a new item to the builded config. Before integration, a function will be called, if
     * set via ::setAddItemPreCallback()
     *
     * @param string            $key
     * @param mixed             $value
     * @param AdjustmentRequest $adjustmentRequest
     * @return ConfigTreeBuilder
     */
    public function addItem($key, $value = '', AdjustmentRequest $adjustmentRequest = null) {
        $nodeKey = \ltrim($this->getCurrentNode() . self::NODEPATH_SEPARATOR . $key, self::NODEPATH_SEPARATOR);
        $this->setNodes(array($nodeKey => $value ), $this->config);

        if(null !== $adjustmentRequest) {
            $adjustmentRequest->setSubject($nodeKey);
            $this->addAdjustmentRequest($adjustmentRequest);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return ConfigTreeBuilder
     */
    public function updateItem($key, $value = '') {
        return $this->addItem($key, $value, null);
    }

    /**
     * @return array
     */
    public function getAdjustmentRequests() {
        return $this->adjustmentRequests;
    }

    /**
     * @param $key
     * @return AdjustmentRequest|bool returns false if there is no adjustment request
     */
    public function getAdjustmentRequest($key) {
        return (isset($this->adjustmentRequests[$key])) ? $this->adjustmentRequests[$key] : false;
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
     * @return bool
     */
    public function isEmpty() {
        return empty($this->config);
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
