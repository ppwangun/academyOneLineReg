<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\View\Model\JsonModel;
use Application\Entity\Role;
use Application\Entity\User;
use Application\Entity\RolePermission;

class RoleController extends AbstractRestfulController
{
    private $entityManager;
    private $cache;
    public function __construct($entityManager,$cache) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $perms = [];
            //getting user ID ans load roles associated to user
            $user= $this->entityManager->getRepository(User::class)->find($id);
            $rolesObject= $user->getRoles();
            $roles = [];
            foreach ($rolesObject as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data= $hydrator->extract($value); 
                $roles[$key]["id"]= $data["id"]; 
                $roles[$key]["name"]= $data["name"]; 
                $roles[$key]["description"]= utf8_encode($data["description"]);
                $roles[$key]["status"]=1;
               
            }

            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $roles
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

            $roles= $this->entityManager->getRepository(Role::class)->findAll();
            $role=[];
          
            foreach($roles  as $key=>$value)
            {

                $role[$key]["id"] = $value->getId();
                $role[$key]["name"] = $value->getName();
                $role[$key]["description"] = $value->getDescription();
                $role[$key]["status"] = 0;
               
              
            }
          
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               
                $role
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
            //Parameter is send as json object

            $role= new Role();
            $role->setName($data["group"]["name"]);
            $role->setDescription($data["group"]["description"]);
            $currentDate = date('Y-m-d H:i:s');
            $role->setDateCreated($currentDate);
            $this->entityManager->persist($role);
            $this->entityManager->flush();
            
           // $role =  $this->entityManager->getRepository(\Application\Entity\Role::Class)->find($role->getId());
            
            foreach($data["permissions"] as $perm)
            { 
                //if permission is checked by the user
                if($perm["status"] == 1)
                {
                    $permission = $this->entityManager->getRepository(\Application\Entity\Permission::Class)->find($perm['id']);
                    
                    $role->addPermission($permission);
                    $this->entityManager->flush();
                  
                }
                
            }
            $this->cache->removeItem('rbac_container');

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $role->getId()
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
            $data =$data["data"];
            $role =  $this->entityManager->getRepository(Role::Class)->find($data["id"]);
            
            $role->setName($data["name"]);
            $role->setDescription($data["description"]);

            $this->entityManager->flush();
            
            
            $rolePerm =  $this->entityManager->getRepository(RolePermission::Class)->findByRole($role);
            
            //delete all permission associated with the role
            foreach($rolePerm as $perm)
            {
                
                $this->entityManager->remove($perm);   
                $this->entityManager->flush();
            }
            
            //adding permission associated with the role
            foreach($data["permissions"] as $perm)
            { 
                //if permission is checked by the user
                if($perm["status"] == 1)
                {
                    $permission = $this->entityManager->getRepository(\Application\Entity\Permission::Class)->find($perm['id']);
                    
                    $role->addPermission($permission);
                    $this->entityManager->flush();
                  
                }
                
            }

            $this->cache->removeItem('rbac_container');
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $role->getId()
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
            
            $role =  $this->entityManager->getRepository(Role::Class)->find($id);
            
            $rolePerm =  $this->entityManager->getRepository(RolePermission::Class)->findByRole($role);
            
            //delete all permission associated with the role
            foreach($rolePerm as $perm)
            {
                
                $this->entityManager->remove($perm);   
                $this->entityManager->flush();
            }
            //deleting role 
            $this->entityManager->remove($role);
            $this->entityManager->flush();
            

            $this->cache->removeItem('rbac_container');
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $role->getId()
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }          

    }
}
