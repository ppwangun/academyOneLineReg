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
use Application\Entity\Degree;
use Application\Entity\TrainingCurriculum;
use Application\Entity\ClassOfStudy;
use Application\Entity\Semester;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\AcademicYear;

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
            $degree = $this->entityManager->getRepository(Degree::class)->find($data['degree_id']);
   
            $curriculum->setDegree($degree); 
            $this->entityManager->persist($curriculum);
            $this->entityManager->flush();
            //Create classes associated with the curriculum
          
            if($data['classesGenerationStatus'])
            {
                if($data['cycleLevel'] == 1)
                {
                    $level = 1;
                    $duration = $level + $data['duration'] ;
                }
                if($data['cycleLevel'] == 2)
                {
                    $level = 4; 
                    $duration = $level + $data['duration'] ;
                }
                if($data['cycleLevel'] == 3)
                {
                    $level = 6; 
                    $duration = $level + $data['duration'] ;
                } 
            
                while ($level < $duration)
                {   
                    $classCode = $data['code'].$level;
                    
                    //checking if such class exists
                    $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classCode);
                  
                    if(!$classe)
                    {
                        $class= new ClassOfStudy();
                        $class->setName($classCode);
                        $class->setCode($classCode);
                        $class->setStudyLevel($level);  
                        $class->setCycle($curriculum);
                        $class->setDegree($degree);
                        $this->entityManager->persist($class); 
                        $this->entityManager->flush();
                         
                        $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBY(["isDefault"=>1]);
                        switch ($level)
                        {
                        
                            case 1: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[1,2],"academicYear"=>$acadYr));break;
                            case 2: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[3,4],"academicYear"=>$acadYr));break;
                            case 3: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[5,6],"academicYear"=>$acadYr));break;
                            case 4: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[7,8],"academicYear"=>$acadYr)); break;
                            case 5: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[9,10],"academicYear"=>$acadYr));break;
                            case 6: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[11,12],"academicYear"=>$acadYr));break;
                            case 7: $sems = $this->entityManager->getRepository(Semester::class)->findBy(array("ranking"=>[13,14],"academicYear"=>$acadYr)); break;   
                        }
                      
                        foreach($sems as $sem)
                        {
                            //Check if the sem is already assigned to class for the current year
                            $isSemAssignedToClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("semester"=>$sem,"classOfStudy"=>$class,"academicYear"=>$acadYr ));
                           
                            if(!$isSemAssignedToClass)
                            {
                                $assignSemToClass = new SemesterAssociatedToClass();
                                $assignSemToClass->setSemester($sem);
                                $assignSemToClass->setClassOfStudy($class);
                                $assignSemToClass->setAcademicYear($acadYr);
                                $this->entityManager->persist($assignSemToClass);
                                $this->entityManager->flush();                                
                            }
                        }
                    }    
                    
                    $level ++;
                }
                
            }

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
            
            
            //Create classes associated with the curriculum
            if($data['classesGenerationStatus'])
            {
                if($data['cycleLevel'] == 1)
                {
                    $level = 1;
                    $duration = $data['duration'];
                }
                if($data['cycleLevel'] == 2)
                {
                    $level = 4; 
                    $duration = $data['duration'] + 4;
                }
                if($data['cycleLevel'] == 3)
                {
                    $level = 6; 
                    $duration = $data['duration'] + 6;
                }                
                while ($level <= $duration)
                {
                    $classCode = $data['code'].$level;
                    
                    //checking if such class exists
                    $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classCode);
                    
                    if(!$classe)
                    {
                        $classe= new ClassOfStudy();
                        $classe->setName($classCode);
                        $classe->setCode($classCode);
                        $classe->setStudyLevel($level);
                        $classe->setCycle($cycle);
                        $this->entityManager->persist($classe);
                    }    
                    
                    $level ++;
                }
            }
            
            
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
