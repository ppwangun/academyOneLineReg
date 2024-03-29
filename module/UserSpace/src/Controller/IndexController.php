<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace UserSpace\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Application\Entity\AllContractsFollowUpView;
use Application\Entity\AllContractsView;

class IndexController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
    }


    public function indexAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    

    
    public function goLecturerAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout5');

        return $view;            

    } 
    
    public function lSubjectsAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout6');

        return $view;            

    }     
    public function lAssignedSubjectsFollowUpTplAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout6');

        return $view;            

    }    
    public function newgrouptplAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function lAssignedSubjectsAction()
    {
        $id = $this->sessionContainer->LoggedInUser["id"];
          
        $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs ,c.teacher  FROM Application\Entity\AllContractsView c '
                .'WHERE c.teacher = :teacher' );
        $query->setParameter('teacher',$id);

        $subjects = $query->getResult();        
        $view = new JsonModel([
           $subjects  
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }  
    public function lAssignedSubjectsFollowUpAction()
    {
        $id = $this->sessionContainer->LoggedInUser["id"];
          
        $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.totalHrsDone ,c.teacher  FROM Application\Entity\AllContractsFollowUpView c '
                .'WHERE c.teacher = :teacher' );
        $query->setParameter('teacher',$id);

        $subjects = $query->getResult();        
        $view = new JsonModel([
           $subjects  
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }    
}
