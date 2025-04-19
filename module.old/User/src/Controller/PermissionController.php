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

use Application\Entity\Permission;
use Application\Entity\Role;

class PermissionController extends AbstractRestfulController
{
    private $entityManager;
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $perms = [];
            //Get the role id and load all associated permissions
            $role= $this->entityManager->getRepository(Role::class)->find($id);
            $permissions = $role->getPermissions();
            foreach ($permissions as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data= $hydrator->extract($value); 
                $perms[$key]["id"]= $data["id"]; 
                $perms[$key]["name"]= $data["name"]; 
                $perms[$key]["description"]= utf8_encode($data["description"]);
                $perms[$key]["status"]=1;
               
            }
           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();

                $hydrator = new ReflectionHydrator();
                $role = $hydrator->extract($role);
                $role["permissions"]=$perms;


            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $role
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

            $permissions = $this->entityManager->getRepository(Permission::class)->findAll();
            $perm=[];
          
            foreach($permissions  as $key=>$value)
            {

                $perm[$key]["id"] = $value->getId();
                $perm[$key]["name"] = $value->getName();
                $perm[$key]["description"] = utf8_encode($value->getDescription());
                $perm[$key]["status"] = 0;
              
            }
          
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               
                $perm 
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }  
    }
}
