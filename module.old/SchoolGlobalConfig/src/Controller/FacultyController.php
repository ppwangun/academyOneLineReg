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
use Application\Entity\School;
use Application\Entity\Faculty;

class FacultyController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($school)
    {
        
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBySchool($school);
       
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        
        return new JsonModel([
                $faculties
        ]);
        
        //return $faculties;
    }
   public function getList()
    {
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBy([],array("name"=>"ASC"));
        
     
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        
        return new JsonModel([
                $faculties
        ]);
        
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
            
            $faculty = new Faculty();
            $faculty->setName($data['name']);
            $faculty->setCode($data['code']);
 
            $school = $this->entityManager->getRepository(School::class)->findOneById($data['school_id']);
     
            $faculty->setSchool($school);
            $this->entityManager->persist($faculty);
            $this->entityManager->flush();
            
            $faculties = $this->getFaculty($school);
            

             
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $faculties
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
            $faculty = $this->entityManager->getRepository(Faculty::class)->findOneById($id);
            if($faculty)
            {
                
                $this->entityManager->remove($faculty);
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
}
