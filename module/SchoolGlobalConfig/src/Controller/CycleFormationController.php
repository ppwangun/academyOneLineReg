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
use Application\Entity\CourseCategory;
use Application\Entity\Faculty;

class CycleFormationController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($data)
    {
        
        $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($data);

            $hydrator = new ReflectionHydrator();
            $cycle = $hydrator->extract($cycle);

            

        
        return new JsonModel([
                $cycle
        ]);
        
        //return $faculties;
    }
   public function getList()
    {
        $cycles = $this->entityManager->getRepository(CourseCategory::class)->findBy([],array("name"=>"ASC"));
        
     
        foreach($cycles as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $cycles[$key] = $data;
        }
        
        return new JsonModel([
                $cycles
        ]);
        
        //return $faculties;
    }
    

    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
            $cycle = new CourseCategory();
            $cycle->setName($data['name']);
            $cycle->setCode($data['code']);
            (isset($data['status']))?$cycle->setStatus($data['status']):$cycle->setStatus(0);
            $this->entityManager->persist($cycle);
            $this->entityManager->flush();
            
        
            

             
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $cycle
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
            $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($id);
            if( $cycle)
            {
                
                $this->entityManager->remove($cycle);
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
            $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($id);
            if($cycle)
            {

                $cycle->setName($data['name']);
                $cycle->setCode($data['code']);
                $cycle->setStatus($data["status"]); 
 
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
