<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\AuthAdapter2;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory class for AuthAdapter service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class AuthAdapter2Factory implements FactoryInterface
{
    /**
     * This method creates the AuthAdapter service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        // Get Doctrine entity manager from Service Manager.
        $entityManager = $container->get('doctrine.entitymanager.orm_default');        
        $numDossier = 0;                
        // Create the AuthAdapter and inject dependency to its constructor.
        return new AuthAdapter2($entityManager,$numDossier);
    }
}