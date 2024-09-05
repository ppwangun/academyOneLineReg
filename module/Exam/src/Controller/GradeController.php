<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Exam;
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\TrainingCurriculum;
use Application\Entity\Grade;


class GradeController extends AbstractRestfulController
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
            $grade = $this->entityManager->getRepository(Grade::class)->findOneById($id);
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($grade);

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
            $grades = $this->entityManager->getRepository(Grade::class)->findAll();

            foreach($grades as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $grades[$key] = $data;
            }

            $this->entityManager->getConnection()->commit();
 
            $output = new JsonModel([
                    $grades
            ]);
            //var_dump($output); //exit();
            return $output;       }
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
            $msge =false;
            $grade = new Grade();
            $grade->setName($data["gradeName"]);
            $this->entityManager->persist($grade);
            $this->entityManager->flush();
      
            if(isset($data["cycle"])&&!is_null($data["cycle"]))
            {
                $cycle = $this->entityManager->getRepository(TrainingCurriculum::class)->findOneByCycleLevel($data["cycle"]);
                $classes= $this->entityManager->getRepository(ClassOfStudy::class)->findByCycle($cycle);
                foreach($classes as $key=>$value)
                {
                   $value->setGrade($grade); 
                   $this->entityManager->flush();
                }
            }
            elseif(isset($data["classes"])&&($data["classes"])){
                foreach($data["classes"] as $classe_id)
                {
                   $class= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_id);
                   $class->setGrade($grade); 
                   $this->entityManager->flush();
                   
                   
                }  
                
            }
            $msge=true;
            
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   $msge
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
           $msge =false;

            $grade = $this->entityManager->getRepository(Grade::class)->findOneById($data['id']);
            $grade->setName($data["gradeName"]);
            $this->entityManager->flush();
            
            if(isset($data["cycle"])&&!is_null($data["cycle"]))
            {
                $cycles = $this->entityManager->getRepository(TrainingCurriculum::class)->findByCycleLevel($data["cycle"]);
                foreach($cycles as $cycle)
                {
                    $classes= $this->entityManager->getRepository(ClassOfStudy::class)->findByCycle($cycle);
                    foreach($classes as $classe)
                    {

                       $classe->setGrade($grade); 
                       $this->entityManager->flush();

                    }
                }
            }
            elseif(isset($data["classes"])&&!is_null($data["classes"])){
                foreach($data["classes"] as $classe_id)
                {
                   $class= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_id);
                   $class->setGrade($grade); 
                   $this->entityManager->flush();
                   
                   
                }                

                
            }
            $msge=true;
            
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   $msge
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
            $grade = $this->entityManager->getRepository(Grade::class)->findOneById($id);  
            $this->entityManager->remove($grade);
            $this->entityManager->flush();
            
           $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   
            ]); 
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }        
    }
    
}
