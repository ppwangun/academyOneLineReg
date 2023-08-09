<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\UserController;
use User\Service\UserManager;



/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class UserControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $cache = $container->get('FilesystemCache');
        return new UserController($entityManager,$userManager,$cache );
    }
}