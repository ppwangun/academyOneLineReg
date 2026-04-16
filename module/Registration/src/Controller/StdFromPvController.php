<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Violet\StreamingJsonEncoder\StreamJsonEncoder; 
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

use Application\Entity\RegisteredStudentView;
use Application\Entity\AllYearsRegisteredStudentView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;

class StdFromPvController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    private $userManager;
    
    public function __construct($entityManager,$sessionContainer,$userManager) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
        $this->userManager = $userManager;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $currentAcadYr = $this->sessionContainer->currentAcadYr;
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findOneBy(["acadYrId"=>$currentAcadYr->getId(),"matricule"=>$id]);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
                $data['dateNaissance']=$data['dateNaissance']->format('Y-m-d');
                $data['dateInscription']=$data['dateInscription']->format('Y-m-d');

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $data
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $userId = $this->sessionContainer->userId;
            $currentAcadYr = $this->sessionContainer->currentAcadYr;;
            $user = $this->sessionContainer->user;
            $registeredStd = [];
            $user= $this->entityManager->getRepository(User::class)->find($userId);
            $classes = $user->getClasses();

            
            //check if user has any admin permission   
            if ($this->userManager->userHasAdminPermission($user)){ 
                       $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("acadYrId"=>$currentAcadYr->getId()),array("nom"=>"ASC"));

            }
            else{

                foreach($classes as $classe)
                {
                    $registeredStd_1 = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe->getCode(),"acadYrId"=>$currentAcadYr->getId()),array("nom"=>"ASC"));
                    $registeredStd = array_merge($registeredStd,$registeredStd_1);
                }
            }
      
            
      
            foreach($registeredStd as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $registeredStd[$key] = $data;
            }

            
           $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    $registeredStd
            ]);
           
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
  private function char($text) { $text = htmlentities($text, ENT_NOQUOTES, "UTF-8"); $text = htmlspecialchars_decode($text); return $text; }

}
