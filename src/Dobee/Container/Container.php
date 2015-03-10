<?php
/**
 * Created by PhpStorm.
 * User: JanHuang
 * Date: 2015/3/11
 * Time: 1:21
 * Email: bboyjanhuang@gmail.com
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Container;

class Container implements ContainerInterface
{
    private $container = array();

    public function get($name)
    {
        if (!isset($this->container[$name])) {
            throw new \InvalidArgumentException(sprintf('Container "%s" is undefined.', $name));
        }
        
        return $this->container[$name];
    }

    public function set($name, $value)
    {
        $this->container[$name] = $value;

        return $this;
    }
}