<?php declare(strict_types=1);

namespace Mf1\TrailingSlashBundle\Exception;

use Symfony\Component\Config\Loader\LoaderInterface;

final class MethodNotFoundException extends \RuntimeException
{
    public function __construct(string $method, LoaderInterface $loader)
    {
        $reflectionClass = new \ReflectionClass($loader);

        $availableMethods = array_map(function(\ReflectionMethod $reflectionMethod) {
            return $reflectionMethod->getShortName();
        }, $reflectionClass->getMethods());

        parent::__construct(sprintf(
            'The method "%s" is not in the routing loader %s. Available methods are: "%s"',
            $method,
            get_class($loader),
            implode('", "', $availableMethods)
        ));
    }
}