<?php

namespace ContainerSxkGjlo;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCategotyServicesService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Services\Category\CategotyServices' shared autowired service.
     *
     * @return \App\Services\Category\CategotyServices
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'symfony'.\DIRECTORY_SEPARATOR.'framework-bundle'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'AbstractController.php';
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Services'.\DIRECTORY_SEPARATOR.'Category'.\DIRECTORY_SEPARATOR.'CategotyServices.php';

        $container->services['App\\Services\\Category\\CategotyServices'] = $instance = new \App\Services\Category\CategotyServices(($container->services['doctrine.orm.default_entity_manager'] ?? $container->getDoctrine_Orm_DefaultEntityManagerService()));

        $instance->setContainer(($container->privates['.service_locator.W9y3dzm'] ?? $container->load('get_ServiceLocator_W9y3dzmService'))->withContext('App\\Services\\Category\\CategotyServices', $container));

        return $instance;
    }
}
