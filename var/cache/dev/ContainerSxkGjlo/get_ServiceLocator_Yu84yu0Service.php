<?php

namespace ContainerSxkGjlo;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_Yu84yu0Service extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.Yu84yu0' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.Yu84yu0'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'services' => ['privates', 'App\\Services\\Logger\\LoggerServices', 'getLoggerServicesService', true],
        ], [
            'services' => 'App\\Services\\Logger\\LoggerServices',
        ]);
    }
}
