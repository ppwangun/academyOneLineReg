<?php
namespace User\Service;

use Laminas\Permissions\Rbac\Rbac;
use Application\Entity\User;

/**
 * This service is used for invoking user-defined RBAC dynamic assertions.
 */
class RbacAssertionManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Auth service.
     * @var Laminas\Authentication\AuthenticationService 
     */
    private $authService;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $authService) 
    {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }
    
    /**
     * This method is used for dynamic assertions. 
     */
    public function assert(Rbac $rbac, $permission, $params)
    {
        $currentUser = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->authService->getIdentity());
        
        if ($permission=='global.system.admin' && $params['user']->getId()==$currentUser->getId())
            return true;        
        if ($permission=='all.classes.view' && $params['user']->getId()==$currentUser->getId())
            return true;
        if ($permission=='manage.groups' && $params['user']->getId()==$currentUser->getId())
            return true;  
        if ($permission=='mark.calculation.rollback' && $params['user']->getId()==$currentUser->getId())
            return true; 
        return false;
                $currentUser = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->authService->getIdentity());
        
        $roles= $params['user']->getRoles(); 
        foreach($roles as $role)
        {
           $permissions = $role->getPermissions();   
            foreach ($permissions as $perm) {
                if ($permission==$perm->getName() && $params['user']->getId()==$currentUser->getId())
                    return true;           
            }
        }

        
        return false;
    }
}
