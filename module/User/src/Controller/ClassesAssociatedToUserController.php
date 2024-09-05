<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\User;
use Application\Entity\ClassOfStudy;
use Application\Entity\UserManagesClassOfStudy;

class ClassesAssociatedToUserController extends AbstractRestfulController
{
    private $entityManager;
    private $userManager;
    
    public function __construct($entityManager,$userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        
    }

    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      

            $arr = [];
           $user = $this->entityManager->getRepository(User::Class)->find($id);
           $classes =  $user->getClasses();// var_dump($classes); exit;
            foreach($classes as $key=>$value)
            {
               
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

               $arr[$key]["id"] =$value->getId();  
               $arr[$key]["code"] =$value->getCode(); 
            }

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $arr
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
    }
    public function create($data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      


           $user = $this->entityManager->getRepository(User::Class)->find($data['user_id']);
           $classe =  $this->entityManager->getRepository(ClassOfStudy::Class)->find($data['class_id']);
           
           //check if classe is already associated to user
           $classeManageByUser  = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(array("user"=>$user,"classOfStudy"=>$classe));
           if(!$classeManageByUser)
           {
                $user->addClasse($classe);
                $this->entityManager->flush();
           }

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $user->getId()
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }          

    }
    
    public function delete($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = json_decode($id,true); 
            
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->find($data['classe_id']);
            $user= $this->entityManager->getRepository(User::class)->find($data['user_id']);
            $assignedClasseToUser = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findOneBy(array("user"=>$user,"classOfStudy"=>$classe ));
            
            if($assignedClasseToUser)
            {
                $this->entityManager->remove($assignedClasseToUser );
                $this->entityManager->flush();
              
            }
            
            $this->entityManager->getConnection()->commit();  

            return new JsonModel([
               // $semesters
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }        
    }
}
