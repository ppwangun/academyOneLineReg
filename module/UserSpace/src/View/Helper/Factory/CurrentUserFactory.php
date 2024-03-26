<?php
namespace User\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use User\View\Helper\CurrentUser;

/**
 * This is the factory for Access view helper. Its purpose is to instantiate the helper
 * and inject dependencies into its constructor.
 */
class CurrentUserFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $entityManager= $container->get('doctrine.entitymanager.orm_default');
        
        return new CurrentUser($entityManager);
    }
}