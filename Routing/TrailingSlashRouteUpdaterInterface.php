<?php declare(strict_types=1);

namespace Mf1\TrailingSlashBundle\Routing;

use Symfony\Component\Routing\RouteCollection;

interface TrailingSlashRouteUpdaterInterface
{
    /* public */ const SLASH_ADD         = 'add';
    /* public */ const SLASH_NO_CHANGE   = 'no_change';
    /* public */ const SLASH_REMOVE      = 'remove';

    /**
     * Update route collection
     * Will update all route's paths (add/remove slash or make no change) depending on rules
     *
     * @param RouteCollection $routeCollection
     * @returns void
     */
    public function updateCollection(RouteCollection $routeCollection) /* : void */;
}