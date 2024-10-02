<?php

namespace Registration\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Application\Entity\RegisteredStudentView;
use Application\Entity\ClassOfStudy;
use Application\Entity\User;

/**
 * This view helper is used to check user permissions.
 */
class CheckUserAccess extends AbstractHelper 
{
    private $entityManager;
    private $examManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$examManager,$sessionContainer) 
    {
        $this->entityManager = $entityManager;
        $this->examManager = $examManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    public function __invoke($matricule,$isAdmin)
    {
                    //Current loggedIn User
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
            //getting student class of study based on matricule
            $std = $this->entityManager->getRepository(RegisteredStudentView::class)->findOneByMatricule($matricule);
            if($std )
            {
                $class_code = $std->getClass();
                $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($class_code);
            }
                      
        return $this->examManager->checkUserCanAccessClass($user,$classe,$isAdmin);
    }
}

