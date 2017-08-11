<?php declare(strict_types=1);

namespace Mf1\TrailingSlashBundle\Routing;

use Mf1\TrailingSlashBundle\Exception\MethodNotFoundException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

final class TrailingSlashLoader implements LoaderInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var TrailingSlashRouteUpdaterInterface
     */
    private $routeUpdater;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader, TrailingSlashRouteUpdaterInterface $routeUpdater)
    {
        $this->loader = $loader;
        $this->routeUpdater = $routeUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $collection = $this->loader->load($resource, $type);

        $this->routeUpdater->updateCollection($collection);

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        return $this->loader->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        $this->loader->setResolver($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $this->loader->supports($resource, $type);
    }

    /**
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function import($resource, $type = null)
    {
        if (!method_exists($this->loader, __METHOD__)) {
            throw new MethodNotFoundException(__METHOD__, $this->loader);
        }

        return $this->loader->import($resource, $type);
    }

    /**
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     * @return LoaderInterface A LoaderInterface instance
     * @throws MethodNotFoundException
     */
    public function resolve($resource, $type = null)
    {
        if (!method_exists($this->loader, __METHOD__)) {
            throw new MethodNotFoundException(__METHOD__, $this->loader);
        }

        return $this->loader->resolve($resource, $type);
    }
}