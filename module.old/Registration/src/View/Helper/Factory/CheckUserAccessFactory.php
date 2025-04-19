<?php
namespace Registration\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Registration\View\Helper\CheckUserAccess;
use Exam\Service\ExamManager;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class CheckUserAccessFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $examManager = $container->get(ExamManager::class);
        $sessionContainer = $container->get('LoggedInUser');
        
        return new CheckUserAccess($entityManager,$examManager,$sessionContainer);
    }
}