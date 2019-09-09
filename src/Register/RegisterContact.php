<?php
/**
 * Author: 沧澜
 * Date: 2019-07-26
 */

namespace CalContainer\Register;

use CalContainer\Exceptions\InstanceNotFoundException;
use CalContainer\Exceptions\RegisterException;
use Exception;
use Psr\Container\ContainerExceptionInterface;

class RegisterContact
{
    
    /**
     * Contract registration constants
     */
    const   REG_METHOD = 'method', REG_CLASS = 'class', REG_NAMESPACE = 'namespace';
    /**
     * @var array
     */
    protected $method = [];
    protected $class = [];
    protected $namespace = [];
    
    /**
     * @param string|array $contact
     * @param string $abstract
     * @param object $instance
     * @return $this
     * @throws RegisterException
     */
    public function adapt($contact, string $abstract, $instance)
    {
        if (is_array($contact)) {
            count($contact) <> 2 || (function () {
                RegisterException::throw('can not binding contract, error: $contact parameter length error in array.');
            })();
            $this->method($contact, $abstract, $instance);
        } else if (is_string($contact)) {
            if (($contacts = explode('@', $contact)) && count($contacts) == 2) {
                $this->method($contact, $abstract, $instance);
            } else if (class_exists($contact)) {
                $this->class($contact, $abstract, $instance);
            } else {
                $this->namespace(trim($contact, '\\'), $abstract, $instance);
            }
        } else {
            RegisterException::throw('$contact parameter must be array or string.');
        }
        return $this;
    }
    
    /**
     * @param string|array $contact
     * @param string $abstract
     * @param mixed $instance
     * @return $this
     */
    public function method($contact, string $abstract, $instance)
    {
        $this->method[implode("@", (array)$contact)][$abstract] = $instance;
        return $this;
    }
    
    /**
     * @param string $class
     * @param string $abstract
     * @param mixed $instance
     * @return $this
     */
    public function class(string $class, string $abstract, $instance)
    {
        $this->class[$class][$abstract] = $instance;
        return $this;
    }
    
    /**
     * @param string $namespace
     * @param string $abstract
     * @param mixed $instance
     * @return $this
     */
    public function namespace(string $namespace, string $abstract, $instance)
    {
        $this->namespace[$namespace][$abstract] = $instance;
        return $this;
    }
    
    /*---------------------------------------------- get && check ----------------------------------------------*/
    
    /**
     * @param string $abstract
     * @param string $namespace
     * @param string $class
     * @param string $method
     * @param mixed $default
     * @return bool|mixed|null
     * @throws Exception
     */
    public function getInAll(string $abstract, string $namespace= null, string $class = null, string $method = null, $default = null)
    {
        if ($class && $method && $this->has(self::REG_METHOD, [$class, $method], $abstract)) {
            return $this->getIn(self::REG_METHOD, [$class, $method], $abstract);
        } elseif ($class && $this->has(self::REG_CLASS, $class, $abstract)) {
            return $this->getIn(self::REG_CLASS, $class, $abstract);
        } elseif ($namespace && $this->has(self::REG_NAMESPACE, $namespace, $abstract)) {
            return $this->getIn(self::REG_NAMESPACE, $namespace, $abstract);
        } else {
            return $default;
        }
    }
    
    /**
     * @param string $property
     * @param mixed $contact
     * @param string $abstract
     * @return bool
     * @throws Exception
     */
    public function has(string $property, $contact, string $abstract)
    {
        return isset(($this->get($property))[implode("@", (array)$contact)][$abstract]);
    }
    
    /**
     * @param string $property
     * @param mixed $contact
     * @param string $abstract
     * @return mixed|null
     * @throws Exception
     */
    public function getIn(string $property, $contact, string $abstract)
    {
        return $this->get($property)[implode("@", (array)$contact)][$abstract] ?? null;
    }
    
    /**
     * @param string $property
     * @return array
     * @throws Exception
     */
    public function get(string $property)
    {
        if (isset($this->{$property})) {
            return $this->{$property};
        } else {
            InstanceNotFoundException::throw("can not get any values in $property key.");
        }
    }
    
}