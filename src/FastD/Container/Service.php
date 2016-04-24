<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/9
 * Time: 上午11:40
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace FastD\Container;

/**
 * Class Service
 *
 * @package FastD\Container
 */
class Service extends ContainerAware
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $constructor;

    /**
     * @var mixed
     */
    protected $instance;

    /**
     * Service constructor.
     * @param $class
     */
    public function __construct($class)
    {
        if (null !== $class) {
            if (is_object($class)) {
                $this->instance = $class;
                $name = get_class($class);
                $this->setName($name);
                $this->setClass($name);
            } else if (false !== strpos($class, '::')) {
                list($name, $constructor) = explode('::', $class);
                $this->setConstructor($constructor);
                $this->setName($name);
                $this->setClass($name);
            } else {
                $this->setName($class);
                $this->setClass($class);
            }

            unset($class);
        }
    }

    /**
     * @return mixed
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * @param mixed $constructor
     * @return $this
     */
    public function setConstructor($constructor)
    {
        $this->constructor = $constructor;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get singleton service object.
     *
     * @param array $arguments
     * @return mixed
     */
    public function singleton(array $arguments = [])
    {
        if (null !== $this->instance) {
            return $this->instance;
        }

        $this->instance = $this->getInstance($arguments);

        return $this->instance;
    }

    /**
     * @param array $arguments
     * @return mixed
     */
    public function getInstance(array $arguments = [])
    {
        if (null === $this->getConstructor()) {
            $reflection = new \ReflectionClass($this->getClass());

            if (null !== $reflection->getConstructor()) {
                $arguments = $this->getParameters($this->getClass(), $reflection->getConstructor()->getName(), $arguments);
            }

            return $reflection->newInstanceArgs($arguments);
        }

        $arguments = $this->getParameters($this->getClass(), $this->getConstructor(), $arguments);

        return call_user_func_array("{$this->getClass()}::{$this->getConstructor()}", $arguments);
    }

    /**
     * @param       $object
     * @param       $method
     * @param array $arguments
     * @return array
     */
    public function getParameters($object, $method, array $arguments = [])
    {
        if (null === $method) {
            return $arguments;
        }

        $reflection = new \ReflectionMethod($object, $method);

        if (0 >= $reflection->getNumberOfParameters()) {
            return $arguments;
        }

        $args = array();

        foreach ($reflection->getParameters() as $index => $parameter) {;
            if (($class = $parameter->getClass()) instanceof \ReflectionClass) {
                $name = $class->getName();
                if (!$this->getContainer()->has($name)) {
                    $this->getContainer()->set($name, $name);
                }

                $args[$index] = $this->getContainer()->singleton($name);
            }
        }

        return array_merge($args, $arguments);
    }

    /**
     * @param       $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments = [])
    {
        if (!method_exists($this->getClass(), $method)) {
            throw new \LogicException(sprintf('Method "%s" is not exists in Class "%s"', $method, $this->getClass()));
        }

        $arguments = $this->getParameters($this->getClass(), $method, $arguments);

        return call_user_func_array([$this->singleton(), $method], $arguments);
    }

    /**
     * @return $this
     */
    public function __clone()
    {
        $this->name = null;
        $this->class = null;
        $this->constructor = null;
        return $this;
    }
}