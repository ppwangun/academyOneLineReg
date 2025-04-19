<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Session\SessionManager;
use User\Service\AuthManager;
use User\Service\UserManager;
use User\Service\RbacAssertionManager;

/**
 * This is the factory class for AuthManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class RbacAssertionManagerFactory implements FactoryInterface
{
    /**
     * This method creates the AuthManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        // Instantiate dependencies.
        $authenticationService = $container->get(\Laminas\Authentication\AuthenticationService::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');  
        
        
                        
        // Instantiate the AuthManager service and inject dependencies to its constructor.
        return new RbacAssertionManager($entityManager,$authenticationService);
    }
}