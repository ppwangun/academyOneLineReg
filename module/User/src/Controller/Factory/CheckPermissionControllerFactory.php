<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use User\Controller\UserController;
use User\Controller\CheckPermissionController;
use User\Service\UserManager;
use Exam\Service\ExamManager;


/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class CheckPermissionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        $sessionContainer = $container->get('LoggedInUser');
        $examManager = $container->get(ExamManager::class);

        return new CheckPermissionController($entityManager,$userManager,$sessionContainer,$examManager );
    }
}