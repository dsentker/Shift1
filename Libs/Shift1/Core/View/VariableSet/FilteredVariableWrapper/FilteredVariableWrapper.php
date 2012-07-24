<?php
namespace Shift1\Core\View\VariableSet\FilteredVariableWrapper;
 
class FilteredVariableWrapper implements \ArrayAccess {

    /**
     * @var mixed
     */
    protected $subject;

    /**
     * @param mixed $subject
     */
    public function __construct($subject) {
        if(is_array($subject))  {
            $subject = new \ArrayObject($subject, \ArrayObject::ARRAY_AS_PROPS);
        }
        $this->subject = $subject;
    }

    /**
     * @param string $key
     * @return null|VariableWrapper
     * @throws Exception
     */
    public function __get($key) {

        if(\property_exists($this->subject, $key))  {

            $requested = $this->subject->{$key};
            if(is_object($requested) || is_array($requested))  {
                return new self($requested);
            } elseif(is_string($requested)) {
                return $this->escape($requested);
            } elseif(is_int($requested)) {
                return  $requested;
            } else {
                throw new Exception("Unknown type of variable: "  .  gettype($requested));
            }
        }

        return $this->notExist($key);
    }

    /**
     * @param string $method
     * @param array $args
     * @return null|VariableWrapper
     */
    public function  __call($method, array $args)  {
        if(is_object($this->subject)  && method_exists($this->subject, $method)) {
            return  new self(\call_user_func_array(array($this->subject, $method), $args));
        }

        return $this->notExist($method, 'Method');
    }

    /**
     * Demo Escaping
     * @param $var
     * @return string
     */
    protected function escape($var)  {
        return  htmlspecialchars($var) .  ' (escaped)';
    }

    /**
     * @param string $var
     * @param string $context
     * @return null
     */
    protected function notExist($var, $context = 'Property') {

        trigger_error($context . ' "' .  $var .  '" does not exist!');
        return null;
    }

    /**
     * @return string
     */
    public function  __toString() {

        if(is_string($this->subject) || (is_object($this->subject) && method_exists($this->subject,  '__toString'))) {
            return $this->escape($this->subject);
        } elseif($this->subject instanceof \Closure)  {
            $method = $this->subject;
            /** @var \Closure $method  */
            $methodReturn = $method();
            $self = new self($methodReturn);
            return $self->__toString();
        } else {
            return gettype($this->subject);
        }

    }

    /**
     * @param integer $offset
     * @return bool
     */
    public function offsetExists($offset) {
        if($this->subject instanceof ArrayObject  && isset($this->subject[$offset])) {
            return true;
        }
        return false;
    }

    /**
     * @param integer $offset
     * @return mixed|null|VariableWrapper
     */
    public function offsetGet($offset)  {
        return ($this->offsetExists($offset))  ?  new self($this->subject[$offset])  : $this->notExist($offset, 'Array Key');
    }

    /**
     * @param integer $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)  {
        if (is_null($offset)) {
            $this->subject[] = $value;
        } else {
            $this->subject[$offset] = $value;
        }
    }

    /**
     * @param integer $offset
     */
    public function offsetUnset($offset )  {
        if($this->offsetExists($offset)) {
            unset($this->subject[$offset]);
        }
    }

}