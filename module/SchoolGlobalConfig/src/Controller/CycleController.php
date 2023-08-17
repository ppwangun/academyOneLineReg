<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Application\Entity\Degree;
use Application\Entity\TrainingCurriculum;

class CycleController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        
        $degree = $this->entityManager->getRepository(Degree::class)->findOneById($id);
  
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($degree);

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
            
            $cycles = $this->entityManager->getRepository(Cycle::class)->findAll();
            
           
            foreach($cycles as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $cycles[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $cycles
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

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
           
            $curriculum= new TrainingCurriculum();  
            $curriculum->setName($data['name']);
            $curriculum->setCode($data['code']);
            $curriculum->setDuration($data['duration']);
            $curriculum->setCycleLevel($data['cycleLevel']);
            $degree = $this->entityManager->getRepository(Degree::class)->findOneById($data['degree_id']);
   
            $curriculum->setDegree($degree); 
            $this->entityManager->persist($curriculum);
            $this->entityManager->flush();
             
            
          
            
            //$degrees = $this->getList();
            $cycles = $this->entityManager->getRepository(TrainingCurriculum::class)->findBy(array('degree'=>$degree));
            foreach($cycles as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $cycles[$key] = $data;
            }
            
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                $cycles
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
            $cycle = $this->entityManager->getRepository(TrainingCurriculum::class)->findOneById($id);
            if($cycle)
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
        try{
            $data= $data['data'];
            
            $cycle =$this->entityManager->getRepository(TrainingCurriculum::class)->findOneById($id);
            $cycle->setName($data['name']);
            $cycle->setCode($data['code']);
            $cycle->setDuration($data['duration']);
            $cycle->setCycleLevel($data['cycleLevel']);
            
            
            $this->entityManager->flush();
            
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);

        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
}
