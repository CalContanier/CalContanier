<?php
/**
 * Created by PhpStorm.
 * User: 沧澜
 * Date: 2019-07-04
 * Annotation:
 */

namespace CalContainer\Register;


use CalContainer\Contracts\RegisterInterface;
use CalContainer\Exceptions\InstanceNotFoundException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RegisterManager implements RegisterInterface
{
    /**
     * @var [] RegisterInterface
     */
    protected $registers = [];
    
    
    /**
     * @param RegisterInterface $register
     * @return $this
     */
    public function push(RegisterInterface $register)
    {
        $this->registers[get_class($register)] = $register;
        return $this;
    }
    
    /**
     * @param RegisterInterface $register
     * @return $this
     */
    public function unshift(RegisterInterface $register)
    {
        $this->registers = [get_class($register) => $register] + $this->registers;
        return $this;
    }
    
    /**
     * @param object|string $register
     * @return RegisterManager
     */
    public function clear($register)
    {
        unset($this->registers[is_object($register) ? get_class($register) : $register]);
        return $this;
    }
    
    /**
     * @return $this
     */
    public function clearAll()
    {
        $this->registers = [];
        return $this;
    }
    
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     */
    public function get($id)
    {
        foreach ($this->registers as $register) {
            if ($register->has($id)) {
                return $register->get($id);
            }
        }
        InstanceNotFoundException::throw('can not find ' . $id . ' in registers.');
    }
    
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        foreach ($this->registers as $register) {
            if ($register->has($id)) {
                return true;
            }
        }
        return false;
    }
}