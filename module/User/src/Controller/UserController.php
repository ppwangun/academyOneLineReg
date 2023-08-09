<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Mvc\Controller\AbstractRestfulController;

use Application\Entity\User;
use Application\Entity\Role;
use Application\Entity\UserRole;

class UserController extends AbstractRestfulController
{
    private $entityManager;
    private $userManager;
    private $cache;
    
    public function __construct($entityManager,$userManager,$cache)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->cache;
        
    }
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 

            $user = $this->entityManager->getRepository(User::class)->find($id);
           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();

                $hydrator = new ReflectionHydrator();
                $user = $hydrator->extract($user);


            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $user
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }       
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 

            $users = $this->entityManager->getRepository(User::class)->findAll();
           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($users as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $users[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $users
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }       
    }
    
    public function create($data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      


            //$data['full_name'] = $data['nom'].' '.$data['prenom'];
            $user = $this->userManager->adduser($data);
            foreach($data["roles"] as $role)
            { 
                //if permission is checked by the user
                if($role["status"] == 1)
                {
                    $role = $this->entityManager->getRepository(\Application\Entity\Role::Class)->find($role['id']);
                    
                    $user->addRole($role);
                    $this->entityManager->flush();
                  
                }
                
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
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $data["data"];
            $user=  $this->entityManager->getRepository(User::Class)->find($data["id"]);
            //updating user
       
            $this->userManager->updateUser($user,$data);
            
            //first delete all roles that user is associated with
            
            $userRole =  $this->entityManager->getRepository(UserRole::Class)->findByUser($user);
            foreach($userRole as $role)
            {
                
                $this->entityManager->remove($role);   
                $this->entityManager->flush();
            }            
            foreach($data["roles"] as $role)
            { 
                //if permission is checked by the user
                if($role["status"] == 1)
                {
                    $role = $this->entityManager->getRepository(Role::Class)->find($role['id']);
                    
                    $user->addRole($role);
                    $this->entityManager->flush();
                  
                }
                
            }


            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  //$role->getId()
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }          

    }  
}
