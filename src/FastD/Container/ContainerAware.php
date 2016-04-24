<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/4/24
 * Time: 下午11:01
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Container;

/**
 * Class ContainerAware
 *
 * @package FastD\Container
 */
abstract class ContainerAware
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $containerInterface
     * @return $this
     */
    public function setContainer(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;

        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}