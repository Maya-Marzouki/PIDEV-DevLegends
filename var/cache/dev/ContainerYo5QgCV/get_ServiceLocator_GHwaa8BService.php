<?php

namespace ContainerYo5QgCV;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_GHwaa8BService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.gHwaa8B' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.gHwaa8B'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService ??= $container->getService(...), [
            'mr' => ['services', 'doctrine', 'getDoctrineService', false],
        ], [
            'mr' => '?',
        ]);
    }
}
