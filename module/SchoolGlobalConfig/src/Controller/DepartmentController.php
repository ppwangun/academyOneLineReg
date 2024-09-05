<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\FieldOfStudy;
use Application\Entity\Department;
use Application\Entity\Faculty;

class DepartmentController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        $dpt= $this->entityManager->getRepository(Department::class)->find($id);

            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($dpt);
            $data["fac_id"] = $dpt->getFaculty()->getId();

        return new JsonModel([
                $data
        ]);
        
        //return $faculties;
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $dpts = $this->entityManager->getRepository(Department::class)->findBy([],array("name"=>"ASC"));
            foreach($dpts as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $dpts[$key] = $data;
                $dpts[$key]["fac_id"] = $value->getFaculty()->getId();
            }
            $this->entityManager->getConnection()->commit();

            return new JsonModel([
                    $dpts
            ]);
            }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
        //return $faculties;
    }    
    public function getFaculty($school)
    {
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBySchool($school);
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        return $faculties;
        
    }
    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {            
            
            $dpt= new Department();
            $dpt->setName($data['name']);
            $dpt->setCode($data['code']);
            $faculty = $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
       
            (isset($data['status']))?$dpt->setStatus($data['status']):$dpt->setStatus(0);
                
            $dpt->setFaculty($faculty); 
            $this->entityManager->persist($dpt);
            $this->entityManager->flush();
            
            $dpts = $this->getList();
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $dpts
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
            $dpt = $this->entityManager->getRepository(Department::class)->findOneById($id);
            if($dpt)
            {
                
                $this->entityManager->remove($dpt);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }


        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);
    }
    
    public function update($id,$data)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $dpt = $this->entityManager->getRepository(Department::class)->findOneById($id);
            if($dpt)
            {

                $dpt->setName($data['name']);
                $dpt->setCode($data['code']);
                $faculty = $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);


                $dpt->setStatus($data["status"]);    
                $dpt->setFaculty($faculty); 
                $this->entityManager->flush();
            }

            $this->entityManager->getConnection()->commit();
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);
    }    
}
