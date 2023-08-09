<?php
namespace User\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Application\Entity\User;

/**
 * This view helper is used to check user permissions.
 */
class CurrentUser extends AbstractHelper 
{
    private $entityManager;
    
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    public function __invoke($login)
    {
        return $this->entityManager->getRepository(User::Class)->findOneByEmail($login);
    }
}