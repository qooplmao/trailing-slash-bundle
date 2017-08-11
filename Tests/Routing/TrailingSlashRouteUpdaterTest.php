<?php declare(strict_types=1);

namespace Mf1\TrailingSlashBundle\Routing;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TrailingSlashRouteUpdaterTest extends TestCase
{
    /**
     * @var TrailingSlashRouteUpdaterInterface
     */
    private $updater;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $rules = [[
            'path'  => '/remove-slash/',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_REMOVE,
        ],[
            'path'  => '/add-slash',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_ADD,
        ],[
            'path'  => '/leave-as-default',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_NO_CHANGE,
        ],[
            'path'  => '/nested/',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_NO_CHANGE,
        ],[
            'path'  => '/nested/add',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_ADD,
        ],[
            'path'  => '/nested/remove/',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_REMOVE,
        ],[
            'path'  => '/nested/remove/no_change',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_NO_CHANGE,
        ],[
            'path'  => '/nested/remove/add',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_ADD,
        ],[
            'path'  => '/nested/remove/remove',
            'slash' => TrailingSlashRouteUpdaterInterface::SLASH_REMOVE,
        ]];

        $this->updater = new TrailingSlashRouteUpdater($rules);
    }

    /**
     * @test
     * @covers \Mf1\TrailingSlashBundle\Routing\TrailingSlashRouteUpdater
     */
    public function it_updates_routes_depending_on_the_rules()
    {
        $tests =  [
            '/remove-slash/'            => '/remove-slash',
            '/add-slash'                => '/add-slash/',
            '/leave-as-default-without' => '/leave-as-default-without',
            '/leave-as-default-with/'   => '/leave-as-default-with/',
            '/nested/'                  => '/nested/',
            '/nested/add'               => '/nested/add/',
            '/nested/remove/'           => '/nested/remove',
            '/nested/remove/no_change'  => '/nested/remove/no_change',
            '/nested/remove/add'        => '/nested/remove/add/',
            '/nested/remove/remove/'    => '/nested/remove/remove',
        ];

        foreach ($tests as $given => $expected) {
            $collection = new RouteCollection();
            $collection->add('test', new Route($given));

            $this->updater->updateCollection($collection);

            $this->assertSame($expected, $collection->get('test')->getPath());
        }
    }
}