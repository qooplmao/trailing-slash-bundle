<?php declare(strict_types=1);

namespace Mf1\TrailingSlashBundle\Routing;

use Assert\Assertion;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class TrailingSlashRouteUpdater implements TrailingSlashRouteUpdaterInterface
{
    /**
     * @var array|array[]
     */
    private $rules;

    /**
     * @param array|array[] $rules
     */
    public function __construct(array $rules)
    {
        Assertion::allKeyExists($rules, 'path');
        Assertion::allKeyExists($rules, 'slash');

        foreach ($rules as $key => $rule) {
            $rules[$key]['path'] = preg_replace('/\/$/', '', $rule['path']);
        }

        usort($rules, function($a, $b) {
            return strcasecmp($b['path'], $a['path']);
        });

        $this->rules = $rules;
    }

    /**
     * {@ionheritdoc}
     */
    public function updateCollection(RouteCollection $routeCollection) /* : void */
    {
        foreach ($routeCollection->all() as $route) {
            if (false !== $update = $this->getUpdateIfRequired($route)) {
                $this->performUpdate($route, $update);
            }
        }
    }

    /**
     * @param Route $route
     * @return bool|string
     */
    private function getUpdateIfRequired(Route $route)
    {
        $path = $route->getPath();

        foreach ($this->rules as $rule) {
            if (0 === strpos($path, $rule['path'])) {
                return $rule['slash'];
            }
        }

        return false;
    }

    /**
     * @param Route $route
     * @param string $update
     * @return void
     */
    private function performUpdate(Route $route, string $update) /* : void */
    {
        $path = $route->getPath();

        if (self::SLASH_REMOVE === $update) {
            $path = preg_replace('/\/$/', '', $path);
        }

        if (self::SLASH_ADD === $update && !preg_match('/\/$/', $path)) {
            $path = $path.'/';
        }

        $route->setPath($path);
    }
}